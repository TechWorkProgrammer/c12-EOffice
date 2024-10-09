<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Mail\PengajuanNotification;
use App\Models\Draft;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DraftController extends Controller
{
    public function index(): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $datas = [];
        switch ($userLogin->role) {
            case 'Tata Usaha':
                $datas = Draft::all();
                break;
            case 'Eksternal':
            case 'Pelaksana':
                $datas = Draft::where('created_by', $userLogin->uuid)->get();
                break;
            case 'Pejabat':
                $pengajuans = Pengajuan::where('penerima_id', $userLogin->uuid)->get();
                foreach ($pengajuans as $pengajuan) {
                    if ($pengajuan->draft->status == 'Proses') {
                        $datas[] = $pengajuan->draft;
                    }
                }
                break;
        }
        return ResponseHelper::Success('drafts retrieved successfully', $datas);
    }

    public function store(Request $request): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $validatedData = $request->validate([
            'perihal' => 'required|string|max:255',
            'file_surat' => 'required|file|mimes:pdf',
        ]);

        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('public/draft');

            $validatedData['file_surat'] = env('APP_URL') . Storage::url($path);
        }

        $validatedData['created_by'] = $userLogin->uuid;

        $draft = Draft::create($validatedData);

        $penerima = $userLogin->pejabat->user;
        if ($userLogin->pejabat->name == 'Pejabat') {
            $penerima = $userLogin->pejabat->atasan->user;
        }

        Pengajuan::create([
            'draft_id' => $draft->uuid,
            'penerima_id' => $penerima->uuid
        ]);

        //Mail::to($penerima->email)->send(new PengajuanNotification($userLogin->name, $penerima, $draft));
        return ResponseHelper::Created('draft created successfully');
    }

    public function konfirmasi(Draft $draftId, Request $request): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();
        $validatedData = $request->validate([
            'status' => 'required|string|max:255',
            'catatan' => 'string|nullable',
            'tanda_tangan' => 'required|file',
        ]);
        if ($request->hasFile('tanda_tangan')) {
            $path = $request->file('tanda_tangan')->store('public/tanda-tangan');

            $validatedData['tanda_tangan'] = env('APP_URL') . Storage::url($path);
        }
        $validatedData['confirmed_at'] = now();
        $pengajuan = $draftId->pengajuans->where('penerima_id', $userLogin->uuid)->first();
        $pengajuan->update($validatedData);
        $penerima = $userLogin->pejabat->atasan;
        if (isset($penerima)) {
            $draftPengajuan = Pengajuan::create([
                'draft_id' => $draftId->uuid,
                'penerima_id' => $penerima->user->uuid,
                'pengajuan_asal' => $pengajuan->uuid
            ]);

            Mail::to($penerima->user->email)->send(new PengajuanNotification($userLogin->name, $penerima->user, $draftPengajuan));

        } else {
            $draftStatus = $pengajuan->status == 'Diterima' ? 'Diterima' : 'Ditolak';
            $draftId->update(['status' => $draftStatus]);
        }
        return ResponseHelper::Success('update pengajuan successfully');
    }

    public function show(Draft $draftId): JsonResponse
    {
        $datas = $draftId->with([
            'creator',
            'pengajuanLevel1.penerima',
            'pengajuanLevel1.pengajuanLevel2.penerima',
            'pengajuanLevel1.pengajuanLevel2.pengajuanLevel3.penerima',
        ])->get();
        return ResponseHelper::Success('drafts retrieved successfully', $datas);
    }

    public function read(Draft $draftId): JsonResponse
    {
        $userLogin = AuthHelper::getAuthenticatedUser();

        $pengajuan = $draftId->pengajuans->where('penerima_id', $userLogin->uuid)->first();
        $pengajuan->read_at = now();
        $pengajuan->save();

        return ResponseHelper::Success('update read at successfully');
    }
}
