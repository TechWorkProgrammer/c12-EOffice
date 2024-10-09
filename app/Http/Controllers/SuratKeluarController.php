<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ResponseHelper;
use App\Models\Draft;
use App\Models\Ekspedisi;
use App\Models\MKlasifikasiSurat;
use App\Models\SuratKeluar;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    public function index(): JsonResponse
    {
        $datas = SuratKeluar::all();
        return ResponseHelper::Success('data for create surat Keluar retrieved successfully', $datas);
    }

    public function create(): JsonResponse
    {
        $datas = MKlasifikasiSurat::all();
        return ResponseHelper::Success('data for create surat Keluar retrieved successfully', $datas);
    }

    public function store(Draft $draftId, Request $request): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $validatedData = $request->validate([
            'klasifikasi_surat_id' => 'required|exists:m_klasifikasi_surats,uuid',
            'tujuan' => 'required|string|max:255',
            'name' => 'required|string|max:255'
        ]);

        $suratKeluar = SuratKeluar::create([
            'nomor_surat' => SuratKeluarController::generateNoSuratKeluar($validatedData['klasifikasi_surat_id']),
            'klasifikasi_surat_id' => $validatedData['klasifikasi_surat_id'],
            'pengirim' => $draftId->created_by,
            'tipe' => $draftId->creator->role == 'Pelaksana' ? 'Internal' : 'Eksternal',
            'perihal' => $draftId->perihal,
            'file_surat' => $draftId->file_surat,
            'tujuan' => $validatedData['tujuan'],
            'created_by' => $userLogin->uuid
        ]);

        Ekspedisi::create([
            'name' => $validatedData['name'],
            'surat_keluar_id' => $suratKeluar->uuid
        ]);

        $draftId->status = 'Terkirim';
        $draftId->save();

        return ResponseHelper::Created('Surat Keluar created successfully');
    }

    public function show(SuratKeluar $suratKeluarId): JsonResponse
    {
        $datas = $suratKeluarId->with(['klasifikasi_surat', 'pengirim', 'creator', 'ekspedisi'])->get();
        return ResponseHelper::Success('surat Keluar retrieved successfully', $datas);
    }

    public function generateNoSuratKeluar($klasifikasiId): string
    {
        $klasifikasi = MKlasifikasiSurat::findOrFail($klasifikasiId);
        $namaKlasifikasi = $klasifikasi->name;

        $tahunSekarang = Carbon::now()->year;
        $bulanSekarang = Carbon::now()->format('n');

        $jumlahSuratTahunIni = SuratKeluar::where('klasifikasi_surat_id', $klasifikasiId)
            ->whereYear('created_at', $tahunSekarang)
            ->count();

        $nomorUrutSurat = $jumlahSuratTahunIni + 1;

        $bulanRomawi = CommonHelper::toRomanNumeral($bulanSekarang);

        return sprintf("SK/%s/%d/%s/%d", $namaKlasifikasi, $nomorUrutSurat, $bulanRomawi, $tahunSekarang);
    }

}
