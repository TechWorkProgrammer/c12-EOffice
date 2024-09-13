<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\PejabatController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;


Route::view('/', "index")->name("home");

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('pejabat', [PejabatController::class, 'index']);

Route::middleware('auth.any')->group(function () {
    Route::prefix('surat-masuk')->group(function () {
        Route::get('', [SuratMasukController::class, 'index']);
        Route::get('create', [SuratMasukController::class, 'create']);
        Route::get('{suratMasukId}', [SuratMasukController::class, 'show']);
        Route::patch('{suratMasukId}/read', [SuratMasukController::class, 'read']);
        Route::post('', [SuratMasukController::class, 'store']);
    
        Route::prefix('{suratId}/disposisi')->group(function () {
            Route::post('', [DisposisiController::class, 'store']);
    
            Route::prefix('{diposisiId}')->group(function () {
                Route::post('', [DisposisiController::class, 'store']);
            });
        });
    });
    
    Route::prefix('disposisi')->group(function () {
        Route::get('create', [DisposisiController::class, 'create']);
        Route::get('{disposisiId}', [DisposisiController::class, 'show']);
        Route::patch('{disposisiId}/read', [DisposisiController::class, 'read']);
        Route::patch('{disposisiId}/done', [DisposisiController::class, 'done']);
    });
    
    Route::prefix('draft')->group(function () {
        Route::get('', [DraftController::class, 'index'])->middleware('auth.any');
        Route::get('{draftId}', [DraftController::class, 'show'])->middleware('auth.any');
        Route::post('{draftId}/konfirmasi', [DraftController::class, 'konfirmasi'])->middleware('auth.pejabat');
        Route::post('', [DraftController::class, 'store'])->middleware('auth.any.pelaksana');
        Route::post('{draftId}/surat-keluar', [SuratKeluarController::class, 'store'])->middleware('auth.tata-usaha');
    });

    Route::prefix('surat-keluar')->group(function () {
        Route::get('create', [SuratKeluarController::class, 'create'])->middleware('auth.any');
        Route::get('', [SuratKeluarController::class, 'index'])->middleware('auth.tata-usaha');
        Route::get('{suratKeluarId}', [SuratKeluarController::class, 'show'])->middleware('auth.tata-usaha');
    });
});
