<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\QuestionerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthenticationController::class, 'registerUser']);

Route::prefix('questioner')->group(function () {
    Route::get('', [QuestionerController::class, 'showQuestion']);
    Route::post('submit', [QuestionerController::class, 'submitQuestion']);
});

Route::prefix('program')->group(function () {
    Route::get('', [ProgramController::class, 'index']);
});
