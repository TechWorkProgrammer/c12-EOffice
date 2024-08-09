<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::all();
        return view('deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        return view('deliveries.create');
    }

    public function store(Request $request)
    {
        $delivery = Delivery::create($request->all());
        return redirect()->route('deliveries.index');
    }

    public function show(Delivery $delivery)
    {
        return view('deliveries.show', compact('delivery'));
    }

    public function edit(Delivery $delivery)
    {
        return view('deliveries.edit', compact('delivery'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $delivery->update($request->all());
        return redirect()->route('deliveries.index');
    }

    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return redirect()->route('deliveries.index');
    }

    public function sopirDelivery(Request $request)
    {
        try {
            // Validasi input dari request
            $validatedData = $request->validate([
                'driver_id' => 'required|exists:users,id',
            ]);

            // Mengambil data delivery dengan status 'on the way' atau 'picked up' dan driver_id yang sesuai
            $deliveries = Delivery::where('driver_id', $validatedData['driver_id'])
                ->where('status', 'on the way')
                ->with(['user:id,name,phone_number,address', 'admin:id,username'])
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at dari yang terbaru
                ->get()
                ->groupBy('type');

            // Mengembalikan response dalam bentuk JSON
            return ResponseHelper::Success('delivery list retrieved successfully', $deliveries);
        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi exception
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function trashPickup(Request $request)
    {
        try {
            // Validasi input dari request
            $validatedData = $request->validate([
                'delivery_id' => 'required|exists:deliveries,id',
            ]);

            // Mengambil data delivery berdasarkan id
            $delivery = Delivery::findOrFail($validatedData['delivery_id']);

            // Memeriksa apakah tipe pengiriman adalah 'pengambilan sampah'
            if ($delivery->type !== 'pengambilan sampah') {
                return ResponseHelper::BadRequest('the delivery is not a trash pickup');
            }

            // Memeriksa apakah status saat ini bukan 'picked up'
            if ($delivery->status === 'picked up') {
                return ResponseHelper::BadRequest('the delivery has already been picked up');
            }

            // Mengubah status menjadi 'picked up'
            $delivery->status = 'picked up';
            $delivery->save();

            // Mengembalikan response dalam bentuk JSON
            return ResponseHelper::Success('delivery status updated to picked up successfully', $delivery);
        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi exception
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function hadiahDelivery(Request $request)
    {
        try {
            // Validasi input dari request
            $validatedData = $request->validate([
                'delivery_id' => 'required|exists:deliveries,id',
            ]);

            // Mengambil data delivery berdasarkan id
            $delivery = Delivery::findOrFail($validatedData['delivery_id']);

            // Memeriksa apakah tipe pengiriman adalah 'pengantaran hadiah'
            if ($delivery->type !== 'pengantaran hadiah') {
                return ResponseHelper::BadRequest('the delivery is not a hadiah delivery');
            }

            // Memeriksa apakah status saat ini sudah 'delivered'
            if ($delivery->status === 'delivered') {
                return ResponseHelper::BadRequest('the delivery has already been delivered');
            }

            // Mengubah status menjadi 'delivered'
            $delivery->status = 'delivered';
            $delivery->save();

            // Mengembalikan response dalam bentuk JSON
            return ResponseHelper::Success('delivery status updated to delivered successfully', $delivery);
        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi exception
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
