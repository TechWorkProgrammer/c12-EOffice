<?php

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
Route::view('/data/pengguna', "data.pengguna")->name("data.pengguna");
Route::view('/data/program', "data.program")->name("data.program");
Route::view('/data/pojok_edukasi', "data.pojok_edukasi")->name("data.pojok_edukasi");
Route::view('/data/edukasi', "data.edukasi")->name("data.edukasi");
Route::view('/data/formulir', "data.formulir")->name("data.formulir");
Route::view('/data/hadiah', "data.hadiah")->name("data.hadiah");
Route::view('/data/admin', "data.admin")->name("data.admin");
