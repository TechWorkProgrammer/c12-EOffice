<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Delivery;
use App\Models\HistoryPoint;
use App\Models\Prize;
use App\Models\Server;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrizeController extends Controller
{
    public function index(): JsonResponse
    {
        $prizes = Prize::all();
        return ResponseHelper::Success('Prizes retrieved successfully', $prizes);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validatedData['possibility'] = 0;

        $prize = Prize::create($validatedData);
        return ResponseHelper::Created('Prize created successfully', $prize);
    }

    public function show(Prize $prize): JsonResponse
    {
        return ResponseHelper::Success('Prize retrieved successfully', $prize);
    }

    public function update(Request $request, Prize $prize): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'possibility' => 'required|numeric|min:0',
        ]);

        $totalPossibility = Prize::where('uuid', '!=', $prize->uuid)->sum('possibility') + $validatedData['possibility'];

        if ($totalPossibility > 100) {
            return ResponseHelper::BadRequest('Total possibility cannot exceed 100.');
        }

        $prize->update($validatedData);
        return ResponseHelper::Success('Prize updated successfully', $prize);
    }

    public function destroy(Prize $prize): JsonResponse
    {
        DB::transaction(function () use ($prize) {
            $remainingPrizes = Prize::where('uuid', '!=', $prize->uuid)->get();

            if ($remainingPrizes->isEmpty()) {
                throw new Exception('Cannot delete the last prize.');
            }

            $distributedPossibility = $prize->possibility / $remainingPrizes->where('possibility', '>', 0)->count();

            foreach ($remainingPrizes as $remainingPrize) {
                if ($remainingPrize->possibility > 0) {
                    $remainingPrize->possibility += $distributedPossibility;
                    $remainingPrize->save();
                }
            }

            $prize->delete();
        });

        return ResponseHelper::Success('Prize deleted successfully');
    }

    public function batchUpdate(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'prizes' => 'required|array',
            'prizes.*.uuid' => 'required|exists:prizes,uuid',
            'prizes.*.possibility' => 'required|numeric|min:0',
        ]);

        $totalPossibility = array_sum(array_column($validatedData['prizes'], 'possibility'));

        if ($totalPossibility !== 100) {
            return ResponseHelper::BadRequest('Total possibility must be exactly 100.');
        }

        DB::transaction(function () use ($validatedData) {
            foreach ($validatedData['prizes'] as $prizeData) {
                $prize = Prize::find($prizeData['uuid']);

                if (!$prize) {
                    throw new Exception("Prize with UUID {$prizeData['uuid']} not found.");
                }

                $prize->update(['possibility' => $prizeData['possibility']]);
            }
        });

        return ResponseHelper::Success('Prizes updated successfully');
    }

    public function claimPrize(): JsonResponse
    {
        $authUser = Auth::guard('api')->user();
        if (!$authUser || empty($authUser->uuid)) {
            return ResponseHelper::Unauthorized('User not authenticated.');
        }

        $user = User::find($authUser->uuid);
        if (!$user) {
            return ResponseHelper::Unauthorized('User not found.');
        }

        $server = Server::first();
        if (!$server) {
            return ResponseHelper::InternalServerError('Server settings not found.');
        }

        $prizePointCost = $server->prize_point;

        $prizes = Prize::where('possibility', '>', 0)->get();
        if ($prizes->isEmpty()) {
            return ResponseHelper::BadRequest('Tidak ada Hadiah yang dapat diambil saat ini.');
        }

        $selectedPrize = $this->getRandomPrizeBasedOnPossibility($prizes);
        if (!$selectedPrize) {
            return ResponseHelper::InternalServerError('Gagal mengambil hadiah.');
        }

        DB::transaction(function () use ($user, $selectedPrize, $prizePointCost) {
            if ($user->point < $prizePointCost) {
                throw new Exception('Point tidak mencukupi.');
            }
            $user->decrement('point', $prizePointCost);

            HistoryPoint::create([
                'user_id' => $user->uuid,
                'prizes_id' => $selectedPrize->uuid,
                'description' => 'Hadiah Yang Didapat: ' . $selectedPrize->name,
                'point' => -$prizePointCost,
            ]);
        });

        return ResponseHelper::Success('Hadiah Berhasil Diambil', $selectedPrize);
    }

    public function listAndCheckPrizes(): JsonResponse
    {
        $user = Auth::guard('api')->user();
        $server = Server::first();
        $prizeExpirationDays = $server->prize_expiration ?? 7;

        $prizes = Prize::whereHas('historyPoints', function ($query) use ($user) {
            if (!empty($user->uuid)) {
                $query->where('user_id', $user->uuid);
            }
        })->with(['historyPoints' => function ($query) use ($user) {
            if (!empty($user->uuid)) {
                $query->where('user_id', $user->uuid);
            }
        }])->get();

        $mappedPrizes = $prizes->map(function ($prize) use ($prizeExpirationDays) {
            $status = 'undelivered';
            $availabilityMessage = 'Available for delivery';
            $historyPoint = $prize->historyPoints->first();
            if ($historyPoint) {
                if ($historyPoint->delivery_id) {
                    $delivery = Delivery::find($historyPoint->delivery_id);
                    $status = $delivery ? $delivery->status : 'undelivered';
                    $availabilityMessage = 'Prize already delivered';
                } else {
                    $obtainedAt = Carbon::parse($historyPoint->created_at);
                    $expirationDate = $obtainedAt->copy()->addDays($prizeExpirationDays);

                    if (Carbon::now()->greaterThan($expirationDate)) {
                        $availabilityMessage = 'Prizes have expired and are no longer available for delivery';
                    }
                }
            }

            return [
                'name' => $prize->name,
                'status' => $status,
                'obtained_at' => $historyPoint ? $historyPoint->created_at : null,
                'availability' => $availabilityMessage,
            ];
        });

        return ResponseHelper::Success('Prizes retrieved successfully.', $mappedPrizes);
    }


    private function getRandomPrizeBasedOnPossibility($prizes)
    {
        $totalWeight = $prizes->sum('possibility');
        $randomWeight = mt_rand(1, (int)($totalWeight * 100)) / 100;

        foreach ($prizes as $prize) {
            if ($randomWeight <= $prize->possibility) {
                return $prize;
            }
            $randomWeight -= $prize->possibility;
        }

        return null;
    }

}
