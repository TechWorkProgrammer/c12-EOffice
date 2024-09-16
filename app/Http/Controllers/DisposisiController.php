<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Models\Disposisi;
use App\Models\IsiDisposisi;
use App\Models\LogDisposisi;
use App\Models\MIsiDisposisi;
use App\Models\SuratMasuk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DisposisiController extends Controller
{

    public function create(): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $isiDisposisis = MIsiDisposisi::all();
        $penerimas = [];
        foreach ($userLogin->pejabat->bawahans as $bawahan) {
            $penerimas[] = $bawahan->user->with('pejabat')->find($bawahan->user->uuid);
        }

        if (isset($penerimas)) {
            foreach ($userLogin->pejabat->pelaksanas->where('role', '!=', 'Pejabat') as $pelaksana) {
                $penerimas[] = $pelaksana;
            }
        }

        $datas = [
            'isi_disposisi' => $isiDisposisis,
            'penerima' => $penerimas
        ];

        return ResponseHelper::Success('data retrieved successfully', $datas);
    }

    public function store(SuratMasuk $suratId, Disposisi $diposisiId, Request $request): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $validatedData = $request->validate([
            'catatan' => 'nullable|string',
            'tanda_tangan' => 'required|file',
            'isi_disposisi_id' => 'required|array',
            'isi_disposisi_id.*' => 'required|uuid|exists:m_isi_disposisis,uuid',
            'penerima' => 'required|array',
            'penerima.*' => 'required|uuid|exists:m_users,uuid',
        ]);

        if ($request->hasFile('tanda_tangan')) {
            $path = $request->file('tanda_tangan')->store('public/tanda-tangan');

            $validatedData['tanda_tangan'] = env('APP_URL') . Storage::url($path);
        }

        $disposisi = Disposisi::create([
            'surat_id' => $suratId->uuid,
            'disposisi_asal' => $diposisiId->uuid,
            'catatan' => $validatedData['catatan'] ?? null,
            'tanda_tangan' => $validatedData['tanda_tangan'],
            'created_by' => $userLogin->uuid,
        ]);

        foreach ($validatedData['isi_disposisi_id'] as $isiDisposisiId) {
            IsiDisposisi::create([
                'disposisi_id' => $disposisi->uuid,
                'isi_disposisi_id' => $isiDisposisiId,
            ]);
        }

        foreach ($validatedData['penerima'] as $penerimaId) {
            LogDisposisi::create([
                'pengirim' => $userLogin->uuid,
                'penerima' => $penerimaId,
                'disposisi_id' => $disposisi->uuid,
            ]);
            //Mail::to($suratId->penerima->email)->send(new SuratMasukNotification($userLogin->name, $suratId));
        }
        return ResponseHelper::Created('Disposisi created successfully');
    }

    public function show(Disposisi $disposisiId): JsonResponse
    {
        $datas = $disposisiId->with([
            'isiDisposisis.isiDisposisi',
            'creator',
            'logDisposisis.penerimaUser',
            'suratMasuk'
        ])->find($disposisiId->uuid);

        return ResponseHelper::Success('data retrieved successfully', $datas);
    }

    public function read(Disposisi $disposisiId): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $logDisposisi = $disposisiId->logDisposisis->where('penerima', $userLogin->uuid)->first();
        $logDisposisi->read_at = now();
        $logDisposisi->save();
        return ResponseHelper::Success('update read at successfully', $logDisposisi);
    }

    public function done(Disposisi $disposisiId): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $logDisposisi = $disposisiId->logDisposisis->where('penerima', $userLogin->uuid)->first();
        $logDisposisi->pelaksanaan_at = now();
        $logDisposisi->save();
        return ResponseHelper::Success('mark as done successfully', $logDisposisi);
    }
}
