<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Disposisi;
use App\Models\LogDisposisi;
use App\Models\MKlasifikasiSurat;
use App\Models\MUser;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userLogin = Auth::guard('api')->user();

        $datas = SuratMasuk::all();

        switch ($userLogin->role) {
            case 'Pejabat':
                // apabila surat masuk langsung ditujukan ke pejabat
                $datas = $datas->where('penerima_id', '=', $userLogin->uuid)->values()->toArray();

                // pejabat menerima disposisi bukan langsung dari surat masuk
                if ($userLogin->pejabat->atasan_id != null) {
                    $logDisposisis = LogDisposisi::where('penerima', '=', $userLogin->uuid)->get();

                    foreach ($logDisposisis as $logDisposisi) {
                        array_push($datas, $logDisposisi->disposisi->suratMasuk);
                    }
                }
                break;
            
            case 'Pelaksana':
                    $datas = [];
                    $logDisposisis = LogDisposisi::where('penerima', '=', $userLogin->uuid)->get();

                    foreach ($logDisposisis as $logDisposisi) {
                        array_push($datas, $logDisposisi->disposisi->suratMasuk);
                    }
                break;
        }

        return ResponseHelper::Success('surat masuk retrieved successfully', $datas);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userHasil = [];
        $userPejabats  = MUser::all()->where('role', '=', 'Pejabat');
        foreach ($userPejabats as $userPejabat) {
            if ($userPejabat->pejabat->atasan_id != null) {
                continue;
            }
            array_push($userHasil, [
                'uuid' => $userPejabat->uuid,
                'name' => $userPejabat->name,
                'jabatan' => $userPejabat->pejabat->name,
                'level_jabatan' => 1,
            ]);

            foreach ($userPejabat->pejabat->bawahans as $bawahan ) {
                array_push($userHasil, [
                    'uuid' => $userPejabat->uuid,
                    'name' => $bawahan->user->name,
                    'jabatan' => $bawahan->user->pejabat->name,
                    'level_jabatan' => 2,
                ]);
            }
        }
        $datas = [
            'm_klasifikasi_surats' => MKlasifikasiSurat::all(),
            'penerimas' => $userHasil
        ];

        return ResponseHelper::Success('data for create surat masuk retrieved successfully', $datas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $userLogin = Auth::guard('api')->user();

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

            $validatedData['nomor_surat'] = SuratMasukController::generateNoSuratMasuk($request->klasifikasi_surat_id);
            $validatedData['created_by'] = $userLogin->uuid;

            $suratMasuk = SuratMasuk::create($validatedData);
            return ResponseHelper::Created('Surat Masuk created successfully', $suratMasuk);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratMasuk $suratMasukId)
    {
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
        return ResponseHelper::Success('data for surat masuk retrieved successfully', $datas);
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

    public function read(SuratMasuk $suratMasukId)
    {
        $userLogin = Auth::guard('api')->user();

        $suratMasukId = $suratMasukId->where('penerima_id', $userLogin->uuid)->first();
        $suratMasukId->read_at = now();
        $suratMasukId->save();
        return ResponseHelper::Success('update read at successfully', $suratMasukId);
    }

    public function generateNoSuratMasuk($klasifikasiId)
    {
        // Ambil nama klasifikasi surat berdasarkan ID
        $klasifikasi = MKlasifikasiSurat::findOrFail($klasifikasiId);
        $namaKlasifikasi = $klasifikasi->name;

        // Ambil tahun dan bulan sekarang
        $tahunSekarang = Carbon::now()->year;
        $bulanSekarang = Carbon::now()->format('n'); // Menggunakan format angka untuk bulan

        // Ambil jumlah surat masuk untuk klasifikasi ini pada tahun ini
        $jumlahSuratTahunIni = SuratMasuk::where('klasifikasi_surat_id', $klasifikasiId)
            ->whereYear('tanggal_surat', $tahunSekarang)
            ->count();

        // Tambahkan 1 untuk mendapatkan urutan surat masuk selanjutnya
        $nomorUrutSurat = $jumlahSuratTahunIni + 1;

        // Ubah bulan ke format Romawi
        $bulanRomawi = SuratMasukController::toRomanNumeral($bulanSekarang);

        // Format nomor surat sesuai dengan ketentuan
        $noSurat = sprintf("SM/%s/%d/%s/%d", $namaKlasifikasi, $nomorUrutSurat, $bulanRomawi, $tahunSekarang);

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
