<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\Delivery;

class TrashController extends Controller
{
    public function trashPickup(Request $request)
    {
        try {
            // Validasi request untuk memastikan user_id ada
            $request->validate([
                'user_id' => 'required|integer|exists:users,id'
            ]);

            // Mengambil user_id dari request
            $userId = $request->input('user_id');

            // Mengambil data dari tabel deliveries dengan tipe pengambilan sampah dan user_id yang diberikan, serta mengurutkan berdasarkan terbaru
            $delivery = Delivery::where('type', 'pengambilan sampah')
                ->where('user_id', $userId)
                ->latest()
                ->first();

            // Cek apakah status adalah 'waiting' dan waktu created_at melebihi 10 menit
            if ($delivery->status === 'waiting' && $delivery->created_at->addMinutes(10)->isPast()) {
                // Update status menjadi 'canceled'
                $delivery->status = 'canceled';
                $delivery->save();

                return ResponseHelper::Success('delivery status updated to canceled due to timeout', $delivery);
            }

            return ResponseHelper::Success('trash pickup retrieved successfully', $delivery);
        } catch (\Exception $e) {
            // Menangani exception dan mengembalikan error message
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function trashPickupRequest(Request $request)
    {
        try {
            // Validasi request untuk memastikan data yang dibutuhkan ada dan valid
            $validatedData = $request->validate([
                'longitude' => 'required|string',
                'latitude' => 'required|string',
                'user_id' => 'required|integer|exists:users,id',
                'description' => 'nullable|string',
            ]);

            // Buat data baru di tabel deliveries
            $delivery = new Delivery();
            $delivery->longitude = $validatedData['longitude'];
            $delivery->latitude = $validatedData['latitude'];
            $delivery->user_id = $validatedData['user_id'];
            $delivery->description = $validatedData['description'];
            $delivery->distance = 1.7;
            $delivery->type = 'pengambilan sampah';
            $delivery->status = 'waiting';
            $delivery->save();

            return ResponseHelper::Created('trash pickup request created successfully', ['delivery' => $delivery]);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function trashPickupRequestCancel(Request $request)
    {
        try {
            // Validasi request untuk memastikan id ada dan valid
            $validatedData = $request->validate([
                'id' => 'required|integer|exists:deliveries,id',
            ]);

            // Temukan delivery berdasarkan ID
            $delivery = Delivery::find($validatedData['id']);

            // Ubah status menjadi 'canceled'
            $delivery->status = 'canceled';
            $delivery->save();

            return ResponseHelper::Success('trash pickup request canceled successfully', $delivery);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function trashPickupHistory(Request $request)
    {
        try {
            // Validasi request untuk memastikan user_id ada dan valid
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            // Ambil history dari request yang pernah dilakukan dengan tipe 'pengambilan sampah'
            $history = Delivery::where('user_id', $validatedData['user_id'])
                ->where('type', 'pengambilan sampah')
                ->orderBy('created_at', 'desc')
                ->get();
            return ResponseHelper::Success('trash pickup history retrieved successfully', $history);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
