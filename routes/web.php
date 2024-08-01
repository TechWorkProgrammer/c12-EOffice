<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HadiahController;
use App\Http\Controllers\PojokEdukasiController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\QuestionerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', "beranda")->name("home");
Route::view('/pengguna', "profile")->name("profile");

//data
// Route::view('/data/pengguna', "data.pengguna")->name("data.pengguna");
// Route::view('/data/program', "data.program")->name("data.program");
// Route::view('/data/pojok_edukasi', "data.pojok_edukasi")->name("data.pojok_edukasi");
// Route::view('/data/edukasi', "data.edukasi")->name("data.edukasi");
// Route::view('/data/formulir', "data.formulir")->name("data.formulir");
// Route::view('/data/hadiah', "data.hadiah")->name("data.hadiah");
// Route::view('/data/admin', "data.admin")->name("data.admin");

Route::prefix('data')->group(function () {
    Route::get('pengguna', [UserController::class, 'index'])->name('data.pengguna');
    Route::get('program', [ProgramController::class, 'index'])->name('data.program');
    Route::get('pojok_edukasi', [PojokEdukasiController::class, 'index'])->name('data.pojok_edukasi');
    Route::get('edukasi', [PojokEdukasiController::class, 'index'])->name('data.edukasi');
    Route::get('formulir', [QuestionerController::class, 'index'])->name('data.formulir');
    Route::get('hadiah', [HadiahController::class, 'index'])->name('data.hadiah');
    Route::get('admin', [AdminController::class, 'index'])->name('data.admin');
});
