<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Delivery;
use App\Models\HistoryPoint;
use App\Models\Server;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeliveryController extends Controller
{
    public function show(Delivery $delivery): JsonResponse
    {
        $delivery->load(['driver', 'admin']);
        return ResponseHelper::Success('Delivery retrieved successfully', $delivery);
    }

    public function getUserDeliveries(): JsonResponse
    {
        $user = Auth::guard('api')->user();

        if (!$user instanceof User) {
            return ResponseHelper::Unauthorized('User not authenticated.');
        }

        $deliveries = Delivery::where('user_id', $user->uuid)
            ->whereIn('status', ['waiting', 'on the way', 'picked up', 'delivered', 'canceled'])
            ->with(['user', 'driver', 'admin'])
            ->get();

        return ResponseHelper::Success('User deliveries retrieved successfully', $deliveries);
    }

    public function getDriverDeliveries(): JsonResponse
    {
        $user = Auth::guard('api')->user();

        if (!$user instanceof User) {
            return ResponseHelper::Unauthorized('User not authenticated.');
        }

        $deliveries = Delivery::where('driver_id', $user->uuid)
            ->whereIn('status', ['on the way', 'picked up'])
            ->with('user', 'admin')
            ->get();

        if ($deliveries->isEmpty()) {
            return ResponseHelper::NotFound('No deliveries found for the driver.');
        }

        return ResponseHelper::Success('Driver deliveries retrieved successfully', $deliveries);
    }

    public function getDriverHistory(): JsonResponse
    {
        $user = Auth::guard('api')->user();

        if (!$user instanceof User) {
            return ResponseHelper::Unauthorized('User not authenticated.');
        }

        $deliveries = Delivery::where('driver_id', $user->uuid)
            ->whereIn('status', ['delivered', 'canceled'])
            ->with('user', 'admin')
            ->get();

        if ($deliveries->isEmpty()) {
            return ResponseHelper::NotFound('No delivery history found for the driver.');
        }

        return ResponseHelper::Success('Driver history retrieved successfully', $deliveries);
    }

    public function getWaitingDeliveries(): JsonResponse
    {
        $deliveries = Delivery::where('status', 'waiting')->get();

        if ($deliveries->isEmpty()) {
            return ResponseHelper::NotFound('No waiting deliveries found.');
        }

        return ResponseHelper::Success('Waiting deliveries retrieved successfully', $deliveries);
    }

    public function getAllDeliveries(): JsonResponse
    {
        $deliveries = Delivery::all();

        if ($deliveries->isEmpty()) {
            return ResponseHelper::NotFound('No deliveries found.');
        }

        return ResponseHelper::Success('All deliveries retrieved successfully', $deliveries);
    }

    /**
     * @throws Exception
     */
    public function deliverPrizes(Request $request): JsonResponse
    {
        $user = Auth::guard('api')->user();

        if (!$user && !$user instanceof User) {
            return ResponseHelper::Unauthorized('User not authenticated.');
        }

        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*' => 'required|uuid|exists:history_points,prizes_id',
        ]);

        $server = Server::first();
        $prizeExpirationDays = $server->prize_expiration ?? 7;

        [$distance, $estimatedTime] = $this->getDistanceAndTime(
            $server->latitude,
            $server->longitude,
            $validatedData['latitude'],
            $validatedData['longitude']
        );

        if ($distance > $server->distance_maximum) {
            return ResponseHelper::BadRequest('Jarak saat ini tidak dijangkau oleh kita.');
        }

        $items = collect($validatedData['items']);
        $deliveries = [];

        DB::transaction(function () use ($estimatedTime, $distance, $user, $items, $prizeExpirationDays, $validatedData, &$deliveries) {
            foreach ($items as $prizeId) {
                $historyPoint = HistoryPoint::where('prizes_id', $prizeId)
                    ->where('user_id', $user->uuid)
                    ->first();

                if (!$historyPoint) {
                    throw new Exception("Hadiah dengan ID $prizeId tidak ditemukan untuk pengguna ini.");
                }

                if ($historyPoint->delivery_id) {
                    throw new Exception("Hadiah dengan ID $prizeId sudah pernah dikirim.");
                }

                $obtainedAt = Carbon::parse($historyPoint->created_at);
                $expirationDate = $obtainedAt->copy()->addDays($prizeExpirationDays);
                if (Carbon::now()->greaterThan($expirationDate)) {
                    throw new Exception("Hadiah dengan ID $prizeId sudah kadaluarsa dan tidak bisa diantarkan lagi.");
                }

                $delivery = Delivery::create([
                    'user_id' => $user->uuid,
                    'latitude' => $validatedData['latitude'],
                    'longitude' => $validatedData['longitude'],
                    'distance' => $distance,
                    'type' => 'pengantaran hadiah',
                    'status' => 'waiting',
                    'description' => 'Pengantaran hadiah: ' . $historyPoint->prize->name,
                    'estimated_time' => $estimatedTime,
                ]);
                $historyPoint->update(['delivery_id' => $delivery->uuid]);

                $deliveries[] = $delivery;
            }
        });

        return ResponseHelper::Success('Permintaan pengantaran berhasil diproses.', $deliveries);
    }

    /**
     * @throws Exception
     */
    public function createDelivery(Request $request): JsonResponse
    {
        $user = Auth::guard('api')->user();
        $server = Server::first();

        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'required|in:pengambilan sampah',
            'description' => 'nullable|string',
        ]);

        [$distance, $estimatedTime] = $this->getDistanceAndTime(
            $server->latitude,
            $server->longitude,
            $validatedData['latitude'],
            $validatedData['longitude']
        );

        if ($distance > $server->distance_maximum) {
            return ResponseHelper::BadRequest('Jarak saat ini tidak dijangkau oleh kita.');
        }
        if (!empty($user->uuid)) {
            $delivery = Delivery::create([
                'user_id' => $user->uuid,
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
                'distance' => $distance,
                'type' => $validatedData['type'],
                'status' => 'waiting',
                'description' => $validatedData['description'] ?? null,
                'estimated_time' => $estimatedTime,
            ]);
        } else return ResponseHelper::Unauthorized('Akun tidak ditemukan');

        return ResponseHelper::Success('Order Berhasil Dilakukan', $delivery);
    }

    /**
     * @throws Exception
     */
    private function getDistanceAndTime($startLat, $startLng, $endLat, $endLng): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => env('OPEN_ROUTE_SERVICE_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openrouteservice.org/v2/directions/driving-car', [
                'coordinates' => [[$startLng, $startLat], [$endLng, $endLat]],
            ]);

            if ($response->failed()) {
                $errorMessage = $response->json('error') ?? $response->body();
                throw new Exception('Gagal Mendapatkan Jarak: ' . $errorMessage);
            }

            $routeData = $response->json();
            $distance = $routeData['routes'][0]['summary']['distance'] / 1000;
            $duration = $routeData['routes'][0]['summary']['duration'] / 60;

            return [$distance, round($duration)];
        } catch (Exception $e) {
            throw new Exception('Gagal Mendapatkan Jarak: ' . $e->getMessage());
        }
    }

    public function assignDriver(Request $request, Delivery $delivery): JsonResponse
    {
        $validatedData = $request->validate([
            'driver_id' => 'required|exists:users,uuid',
        ]);

        $driver = User::where('uuid', $validatedData['driver_id'])->where('role', User::ROLE_DRIVER)->first();

        if (!$driver) {
            return ResponseHelper::BadRequest('Assigned user is not a driver.');
        }
        $admin = Auth::guard('api')->user();
        $uuid = '';
        if (!empty($admin->uuid)) {
            $uuid = $admin->uuid;
        }

        $delivery->update([
            'driver_id' => $validatedData['driver_id'],
            'admin_id' => $uuid,
            'status' => 'on the way',
            'confirmed_time' => Carbon::now(),
        ]);

        return ResponseHelper::Success('Driver assigned successfully', $delivery);
    }

    public function markAsPickedUp(Delivery $delivery): JsonResponse
    {
        if ($delivery->status !== 'on the way') {
            return ResponseHelper::BadRequest('Delivery is not on the way.');
        }

        if ($delivery->type === 'pengantaran hadiah') {
            DB::transaction(function () use ($delivery) {
                $delivery->update(['status' => 'delivered']);
                HistoryPoint::create([
                    'user_id' => $delivery->user->uuid,
                    'delivery_id' => $delivery->uuid,
                    'description' => 'Pengantaran hadiah selesai',
                    'point' => 0,
                ]);
            });

            return ResponseHelper::Success('Prize delivery marked as delivered', $delivery);
        }

        $delivery->update(['status' => 'picked up']);

        return ResponseHelper::Success('Delivery marked as picked up', $delivery);
    }

    public function finalizeDelivery(Request $request, Delivery $delivery): JsonResponse
    {
        $server = Server::first();

        $validatedData = $request->validate([
            'weight' => 'required|numeric|min:0.1',
        ]);

        $pointsAwarded = max(1, (int)ceil($validatedData['weight'] * $server->point_conversion_rate));

        DB::transaction(function () use ($delivery, $pointsAwarded, $validatedData) {
            $delivery->update([
                'status' => 'delivered',
                'weight' => $validatedData['weight'],
            ]);

            $user = $delivery->user;
            $user->increment('point', $pointsAwarded);

            HistoryPoint::create([
                'user_id' => $user->uuid,
                'delivery_id' => $delivery->uuid,
                'description' => 'Point diterima dari penjemputan: ' . $delivery->type,
                'point' => $pointsAwarded,
            ]);
        });

        return ResponseHelper::Success('Order selesai dan point di dapatkan', $delivery);
    }

}
