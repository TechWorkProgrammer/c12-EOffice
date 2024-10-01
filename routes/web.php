<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\KotamaController;
use App\Http\Controllers\PejabatController;
use App\Http\Controllers\SatminkalController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::view('/', "index")->name("home");

Route::middleware('json.request')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('kotama')->group(function () {
        Route::get('', [KotamaController::class, 'index']);
        Route::post('', [KotamaController::class, 'store'])->middleware('auth.administrator');
    });

    Route::prefix('satminkal')->group(function () {
        Route::get('', [SatminkalController::class, 'index']);
        Route::post('', [SatminkalController::class, 'store'])->middleware('auth.administrator');
    });

    Route::prefix('pejabat')->group(function () {
        Route::get('', [PejabatController::class, 'index']);
        Route::post('', [PejabatController::class, 'store'])->middleware('auth.administrator');
    });

    Route::prefix('signature')->group(function () {
        Route::get('', [SignatureController::class, 'index'])->middleware('auth.any');
        Route::get('{signatureId}', [SignatureController::class, 'show']);
        Route::post('', [SignatureController::class, 'store'])->middleware('auth.any');
    });

    Route::middleware('auth.administrator')->prefix('admin')->group(function () {
        Route::get('user', [AdminController::class, 'allUser']);
    });

    Route::middleware('auth.any')->group(function () {
        Route::prefix('surat-masuk')->group(function () {
            Route::get('', [SuratMasukController::class, 'index']);
            Route::post('log/user/{userId}', [SuratMasukController::class, 'logUser']);
            Route::get('create', [SuratMasukController::class, 'create']);
            Route::get('{suratMasukId}', [SuratMasukController::class, 'show']);
            Route::put('{suratMasukId}/done', [SuratMasukController::class, 'done'])->middleware('auth.any.pelaksana');
            Route::post('', [SuratMasukController::class, 'store']);

            Route::prefix('{suratId}/disposisi')->group(function () {
                Route::post('', [DisposisiController::class, 'store']);
                Route::prefix('{disposisiId}')->group(function () {
                    Route::post('', [DisposisiController::class, 'store']);
                });
            });
        });

        Route::prefix('disposisi')->group(function () {
            Route::get('create', [DisposisiController::class, 'create'])->middleware('auth.pejabat');
            Route::get('{disposisiId}', [DisposisiController::class, 'show']);
            Route::patch('{disposisiId}/read', [DisposisiController::class, 'read']);
            Route::patch('{disposisiId}/done', [DisposisiController::class, 'done']);
        });

        Route::prefix('draft')->group(function () {
            Route::get('', [DraftController::class, 'index']);
            Route::get('{draftId}', [DraftController::class, 'show']);
            Route::post('{draftId}/konfirmasi', [DraftController::class, 'konfirmasi'])->middleware('auth.pejabat');
            Route::post('', [DraftController::class, 'store'])->middleware('auth.any.pelaksana');
            Route::post('{draftId}/surat-keluar', [SuratKeluarController::class, 'store'])->middleware('auth.tata-usaha');
        });

        Route::prefix('surat-keluar')->group(function () {
            Route::get('create', [SuratKeluarController::class, 'create']);
            Route::get('', [SuratKeluarController::class, 'index'])->middleware('auth.tata-usaha');
            Route::get('{suratKeluarId}', [SuratKeluarController::class, 'show'])->middleware('auth.tata-usaha');
        });
    });
});


Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    if (!Storage::disk('public')->exists($folder . '/' . $filename)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($folder . '/' . $filename);
    $type = Storage::disk('public')->mimeType($folder . '/' . $filename);

    return response($file, 200)->header('Content-Type', $type);
});


