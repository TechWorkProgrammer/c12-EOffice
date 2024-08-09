<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Delivery;
use App\Models\Hadiah;
use App\Models\HistoryPoint;
use App\Models\User;
use Illuminate\Http\Request;

class HadiahController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Cek apakah request meminta JSON
            if ($request->wantsJson()) {
                // Validasi request untuk memastikan user_id ada dan valid
                $validatedData = $request->validate([
                    'user_id' => 'required|integer|exists:users,id',
                ]);

                // Ambil data user berdasarkan user_id
                $user = User::find($validatedData['user_id']);

                // Ambil data hadiah kecuali atribut possibility
                $hadiah = Hadiah::select('id', 'name', 'point', 'image')->get();

                $data = [
                    'hadiah' => $hadiah,
                    'user' => [
                        'id' => $user->id,
                        'point' => $user->point
                    ]
                ];

                return ResponseHelper::Success('hadiah retrieved successfully', $data);
            } else {
                // Ambil semua data hadiah untuk request biasa
                $hadiah = Hadiah::all();
                return view('data.hadiah', compact('hadiah'));
            }
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function create()
    {
        return view('hadiahs.create');
    }

    public function store(Request $request)
    {
        $hadiah = Hadiah::create($request->all());
        return redirect()->route('hadiahs.index');
    }

    public function show(Hadiah $hadiah)
    {
        return view('hadiahs.show', compact('hadiah'));
    }

    public function edit(Hadiah $hadiah)
    {
        return view('hadiahs.edit', compact('hadiah'));
    }

    public function update(Request $request, Hadiah $hadiah)
    {
        $hadiah->update($request->all());
        return redirect()->route('hadiahs.index');
    }

    public function destroy(Hadiah $hadiah)
    {
        $hadiah->delete();
        return redirect()->route('hadiahs.index');
    }

    public function redeem(Request $request)
    {
        try {
            // Validasi request untuk memastikan user_id ada dan valid
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            // Ambil data user berdasarkan user_id
            $user = User::find($validatedData['user_id']);

            // Ambil semua hadiah dan lakukan pengundian berdasarkan possibility
            $hadiahList = Hadiah::all();
            $totalProbability = $hadiahList->sum('possibility');

            $randomNumber = mt_rand(0, $totalProbability * 100) / 100;
            $currentProbability = 0;
            $selectedHadiah = null;

            foreach ($hadiahList as $hadiah) {
                $currentProbability += $hadiah->possibility;
                if ($randomNumber <= $currentProbability) {
                    $selectedHadiah = $hadiah;
                    break;
                }
            }

            if ($selectedHadiah) {
                // Cek apakah user memiliki cukup poin untuk menebus hadiah
                if ($user->point >= $selectedHadiah->point) {
                    // Kurangi poin user
                    $user->point -= $selectedHadiah->point;
                    $user->save();

                    // Buat entri baru di tabel history_points
                    $historyPoint = new HistoryPoint();
                    $historyPoint->user_id = $user->id;
                    $historyPoint->hadiah_id = $selectedHadiah->id;
                    $historyPoint->description = 'Mengambil Hadiah ' . $selectedHadiah->name;
                    $historyPoint->point = -$selectedHadiah->point;
                    $historyPoint->save();

                    return ResponseHelper::Success('hadiah successfully taken', $selectedHadiah);
                } else {
                    return ResponseHelper::BadRequest('Poin tidak cukup untuk mengambil hadiah ini');
                }
            } else {
                return ResponseHelper::BadRequest('Tidak ada hadiah yang tersedia saat ini');
            }
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function delivery(Request $request)
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
            $delivery->type = 'pengantaran hadiah';
            $delivery->status = 'waiting';
            $delivery->save();

            // Mengambil data history_point dari user dengan delivery_id null dan diurutkan dari yang terbaru
            $historyPoint = HistoryPoint::where('user_id', $validatedData['user_id'])
                ->whereNull('delivery_id')
                ->latest() // Urutkan berdasarkan created_at dari yang terbaru
                ->first();

            $historyPoint->delivery_id = $delivery->id;
            $historyPoint->save();

            return ResponseHelper::Created('hadiah delivery request created successfully', ['delivery' => $delivery]);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function pointsHistory(Request $request)
    {
        try {
            // Validasi request untuk memastikan data yang dibutuhkan ada dan valid
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            // Mengambil data history_point untuk user yang diminta
            $historyPoints = HistoryPoint::where('user_id', $validatedData['user_id'])
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at dari yang terbaru
                ->get();

            // Mengembalikan response dalam bentuk JSON
            return ResponseHelper::Success('history points retrieved successfully', $historyPoints);
        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi exception
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
