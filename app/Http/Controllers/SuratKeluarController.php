<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Draft;
use App\Models\Ekspedisi;
use App\Models\MKlasifikasiSurat;
use App\Models\SuratKeluar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = SuratKeluar::all();
        return ResponseHelper::Success('data for create surat Keluar retrieved successfully', $datas);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datas = MKlasifikasiSurat::all();

        return ResponseHelper::Success('data for create surat Keluar retrieved successfully', $datas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Draft $draftId, Request $request)
    {
        try {
            $userLogin = Auth::guard('api')->user();
    
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
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(SuratKeluar $suratKeluarId)
    {
        $datas = $suratKeluarId->with(['klasifikasi_surat', 'pengirim', 'creator', 'ekspedisi'])->get();
        return ResponseHelper::Success('surat Keluar retrieved successfully', $datas);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function generateNoSuratKeluar($klasifikasiId)
    {
        // Ambil nama klasifikasi surat berdasarkan ID
        $klasifikasi = MKlasifikasiSurat::findOrFail($klasifikasiId);
        $namaKlasifikasi = $klasifikasi->name;

        // Ambil tahun dan bulan sekarang
        $tahunSekarang = Carbon::now()->year;
        $bulanSekarang = Carbon::now()->format('n'); // Menggunakan format angka untuk bulan

        // Ambil jumlah surat Keluar untuk klasifikasi ini pada tahun ini
        $jumlahSuratTahunIni = SuratKeluar::where('klasifikasi_surat_id', $klasifikasiId)
            ->whereYear('created_at', $tahunSekarang)
            ->count();

        // Tambahkan 1 untuk mendapatkan urutan surat Keluar selanjutnya
        $nomorUrutSurat = $jumlahSuratTahunIni + 1;

        // Ubah bulan ke format Romawi
        $bulanRomawi = SuratKeluarController::toRomanNumeral($bulanSekarang);

        // Format nomor surat sesuai dengan ketentuan
        $noSurat = sprintf("SK/%s/%d/%s/%d", $namaKlasifikasi, $nomorUrutSurat, $bulanRomawi, $tahunSekarang);

        return $noSurat;
    }

    public function toRomanNumeral($number)
    {
        $map = [
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $value) {
                if ($number >= $value) {
                    $number -= $value;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
