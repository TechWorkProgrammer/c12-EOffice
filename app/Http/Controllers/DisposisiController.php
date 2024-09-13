<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Disposisi;
use App\Models\IsiDisposisi;
use App\Models\LogDisposisi;
use App\Models\MIsiDisposisi;
use App\Models\MUser;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $userLogin = Auth::guard('api')->user();

            $isiDisposisis = MIsiDisposisi::all();
            $penerimas = [];
            foreach ($userLogin->pejabat->bawahans as $bawahan) {
                array_push($penerimas, $bawahan->user->with('pejabat')->find($bawahan->user->uuid));
            }

            if (isset($penerimas)) {
                foreach ($userLogin->pejabat->pelaksanas->where('role', '!=', 'Pejabat') as $pelaksana) {
                    array_push($penerimas, $pelaksana);
                }
            }

            $datas = [
                'isi_disposisi'=>$isiDisposisis,
                'penerima'=>$penerimas
            ];

            return ResponseHelper::Success('data retrieved successfully', $datas);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuratMasuk $suratId, Disposisi $diposisiId, Request $request)
    {
        try {
            $userLogin = Auth::guard('api')->user();

            // Validasi data disposisi
            $validatedData = $request->validate([
                'catatan' => 'nullable|string',
                'tanda_tangan' => 'required|file',
                'isi_disposisi_id' => 'required|array', // array id isi disposisi
                'isi_disposisi_id.*' => 'required|uuid|exists:m_isi_disposisis,uuid', // validasi setiap id dalam array
                'penerima' => 'required|array', // array id penerima log disposisi
                'penerima.*' => 'required|uuid|exists:m_users,uuid', // validasi setiap id dalam array penerima
            ]);

            if ($request->hasFile('tanda_tangan')) {
                $path = $request->file('tanda_tangan')->store('public/tanda-tangan');

                $validatedData['tanda_tangan'] = env('APP_URL') . Storage::url($path);
            }

            // 1. Simpan data disposisi
            $disposisi = Disposisi::create([
                'surat_id' => $suratId->uuid,
                'disposisi_asal' => $diposisiId->uuid,
                'catatan' => $validatedData['catatan'] ?? null, // optional
                'tanda_tangan' => $validatedData['tanda_tangan'],
                'created_by' => $userLogin->uuid,
            ]);

            // 2. Simpan isi disposisi
            foreach ($validatedData['isi_disposisi_id'] as $isiDisposisiId) {
                IsiDisposisi::create([
                    'disposisi_id' => $disposisi->uuid,
                    'isi_disposisi_id' => $isiDisposisiId,
                ]);
            }

            // 3. Simpan log disposisi
            foreach ($validatedData['penerima'] as $penerimaId) {
                LogDisposisi::create([
                    'pengirim' => $userLogin->uuid,
                    'penerima' => $penerimaId,
                    'disposisi_id' => $disposisi->uuid,
                ]);
            }

            return ResponseHelper::Created('Disposisi created successfully');
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Disposisi $disposisiId) {
        $datas = $disposisiId->with([
            'isiDisposisis.isiDisposisi',
            'creator',
            'logDisposisis.penerimaUser',
            'suratMasuk'
        ])->find($disposisiId->uuid);

        return ResponseHelper::Success('data retrieved successfully', $datas);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disposisi $disposisi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disposisi $disposisi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disposisi $disposisi)
    {
        //
    }

    public function read(Disposisi $disposisiId)
    {
        $userLogin = Auth::guard('api')->user();

        $logDisposisi = $disposisiId->logDisposisis->where('penerima', $userLogin->uuid)->first();
        $logDisposisi->read_at = now();
        $logDisposisi->save();
        return ResponseHelper::Success('update read at successfully', $logDisposisi);
    }

    public function done(Disposisi $disposisiId)
    {
        $userLogin = Auth::guard('api')->user();

        $logDisposisi = $disposisiId->logDisposisis->where('penerima', $userLogin->uuid)->first();
        $logDisposisi->pelaksanaan_at = now();
        $logDisposisi->save();
        return ResponseHelper::Success('mark as done successfully', $logDisposisi);
    }
}
