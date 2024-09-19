<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ResponseHelper;
use App\Models\LogDisposisi;
use App\Models\MKlasifikasiSurat;
use App\Models\MUser;
use App\Models\SuratMasuk;
use App\Models\UserStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();
        $datas = [];
        $suratMasuksQuery = SuratMasuk::with(['creator', 'penerima', 'klasifikasiSurat', 'userStatus' => function ($query) use ($userLogin) {
            $query->where('user_id', $userLogin->uuid);
        }]);

        switch ($userLogin->role) {
            case 'Pejabat':
                $datas = $suratMasuksQuery->where('penerima_id', $userLogin->uuid)->get();
                if ($userLogin->pejabat->atasan_id !== null) {
                    $logDisposisis = LogDisposisi::where('penerima', $userLogin->uuid)->get();

                    foreach ($logDisposisis as $logDisposisi) {
                        $suratMasuk = $logDisposisi->disposisi->suratMasuk;
                        if ($suratMasuk) {
                            $suratMasuk->load(['creator', 'penerima', 'klasifikasiSurat']);
                            $datas[] = $suratMasuk;
                        }
                    }
                }
                break;

            case 'Pelaksana':
                $logDisposisis = LogDisposisi::where('penerima', $userLogin->uuid)->get();
                foreach ($logDisposisis as $logDisposisi) {
                    $suratMasuk = $logDisposisi->disposisi->suratMasuk;
                    if ($suratMasuk) {
                        $suratMasuk->load(['creator', 'penerima', 'klasifikasiSurat']);
                        $datas[] = $suratMasuk;
                    }
                }
                break;
            default:
                $datas = $suratMasuksQuery->get();
                break;
        }

        foreach ($datas as $suratMasuk) {
            if (is_null($suratMasuk->userStatus)) {
                $userStatus = UserStatus::firstOrCreate(
                    ['user_id' => $userLogin->uuid, 'surat_masuk_id' => $suratMasuk->uuid],
                    ['read_at' => null, 'pelaksanaan_at' => null]
                );

                $suratMasuk->setRelation('userStatus', $userStatus);
            }
        }

        return ResponseHelper::Success('surat masuk retrieved successfully', $datas);
    }


    public function create(): JsonResponse
    {
        $userHasil = [];
        $userPejabats = MUser::where('role', 'Pejabat')->get();

        foreach ($userPejabats as $userPejabat) {
            if ($userPejabat->pejabat->atasan_id != null) {
                continue;
            }

            $userHasil[] = [
                'uuid' => $userPejabat->uuid,
                'name' => $userPejabat->name,
                'jabatan' => $userPejabat->pejabat->name ?? 'N/A',
                'level_jabatan' => 1,
            ];

            foreach ($userPejabat->pejabat->bawahans as $bawahan) {
                $userHasil[] = [
                    'uuid' => $bawahan->user->uuid,
                    'name' => $bawahan->user->name,
                    'jabatan' => $bawahan->user->pejabat->name ?? 'N/A',
                    'level_jabatan' => 2,
                ];
            }
        }

        $datas = [
            'classifications' => MKlasifikasiSurat::all(),
            'users' => $userHasil
        ];

        return ResponseHelper::Success('data for create surat masuk retrieved successfully', $datas);
    }


    public function store(Request $request): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $validatedData = $request->validate([
            'klasifikasi_surat_id' => 'required|exists:m_klasifikasi_surats,uuid',
            'tanggal_surat' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'file_surat' => 'required|file|mimes:pdf',
            'penerima_id' => 'required|exists:m_users,uuid',
        ]);

        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('public/surat-masuk');

            $validatedData['file_surat'] = env('APP_URL') . Storage::url($path);
        }

        $validatedData['nomor_surat'] = SuratMasukController::generateNoSuratMasuk($request["klasifikasi_surat_id"]);
        $validatedData['created_by'] = $userLogin->uuid;

        $suratMasuk = SuratMasuk::create($validatedData);

        //Mail::to($suratMasuk->penerima->email)->send(new SuratMasukNotification($userLogin->name, $suratMasuk));

        return ResponseHelper::Created('Surat Masuk created successfully', $suratMasuk);
    }

    public function show(SuratMasuk $suratMasukId): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $userStatus = UserStatus::firstOrCreate(
            ['user_id' => $userLogin->uuid, 'surat_masuk_id' => $suratMasukId->uuid],
            ['read_at' => null, 'pelaksanaan_at' => null]
        );

        if (is_null($userStatus->read_at)) {
            $userStatus->read_at = now();
            $userStatus->save();
        }

        $datas = $suratMasukId->with([
            'disposisi' => function ($query) {
                $query->where('disposisi_asal', null)->with([
                    'isiDisposisis.isiDisposisi',
                    'creator',
                    'logDisposisis.penerimaUser',
                    'disposisiLevel2' => function ($query) {
                        $query->with([
                            'isiDisposisis.isiDisposisi',
                            'creator',
                            'logDisposisis.penerimaUser',
                            'disposisiLevel3' => function ($query) {
                                $query->with([
                                    'isiDisposisis.isiDisposisi',
                                    'creator',
                                    'logDisposisis.penerimaUser',
                                ]);
                            }
                        ]);
                    }
                ]);
            },
            'klasifikasiSurat',
            'creator',
            'penerima.pejabat',
        ])->find($suratMasukId->uuid);

        $datas["user_status"] = $userStatus;
        $datas["log_status"] = UserStatus::where('surat_masuk_id', $suratMasukId->uuid)->get();
        return ResponseHelper::Success('data for surat masuk retrieved successfully', $datas);
    }

    public function done(SuratMasuk $suratMasukId): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $userStatus = UserStatus::where('surat_masuk_id', $suratMasukId->uuid)->where('user_id', $userLogin->uuid)->first();
        $userStatus->update(['pelaksanaan_at' => now()]);

        return ResponseHelper::Success('mark as done successfully', $userStatus);
    }

    public function generateNoSuratMasuk($klasifikasiId): string
    {
        $klasifikasi = MKlasifikasiSurat::findOrFail($klasifikasiId);
        $namaKlasifikasi = $klasifikasi->name;

        $tahunSekarang = Carbon::now()->year;
        $bulanSekarang = Carbon::now()->format('n');

        $jumlahSuratTahunIni = SuratMasuk::where('klasifikasi_surat_id', $klasifikasiId)
            ->whereYear('tanggal_surat', $tahunSekarang)
            ->count();

        $nomorUrutSurat = $jumlahSuratTahunIni + 1;

        $bulanRomawi = CommonHelper::toRomanNumeral($bulanSekarang);
        return sprintf("SM/%s/%d/%s/%d", $namaKlasifikasi, $nomorUrutSurat, $bulanRomawi, $tahunSekarang);
    }
}
