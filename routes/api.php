<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\HadiahController;
use App\Http\Controllers\PojokEdukasiController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\QuestionerController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\UserController;
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
Route::post('login', [AuthenticationController::class, 'loginUser']);

Route::prefix('otp')->group(function () {
    Route::post('send', [AuthenticationController::class, 'sendOtp']);
    Route::post('verify', [AuthenticationController::class, 'verifyOtp']);
});

Route::prefix('questioner')->group(function () {
    Route::get('', [QuestionerController::class, 'showQuestion']);
    Route::post('submit', [QuestionerController::class, 'submitQuestion']);
});

Route::get('user/home', [UserController::class, 'home']);

Route::prefix('program')->group(function () {
    Route::get('', [ProgramController::class, 'index']);
});

Route::prefix('pojok-edukasi')->group(function () {
    Route::get('', [PojokEdukasiController::class, 'index']);
});

Route::prefix('trash')->group(function () {
    Route::get('pickup', [TrashController::class, 'trashPickup']);
    Route::post('pickup/request', [TrashController::class, 'trashPickupRequest']);
    Route::post('pickup/request/cancel', [TrashController::class, 'trashPickupRequestCancel']);
    Route::get('pickup/history', [TrashController::class, 'trashPickupHistory']);
});

Route::prefix('hadiah')->group(function () {
    Route::get('', [HadiahController::class, 'index']);
    Route::post('redeem', [HadiahController::class, 'redeem']);
    Route::post('delivery', [HadiahController::class, 'delivery']);
});

Route::get('point/history', [HadiahController::class, 'pointsHistory']);

Route::prefix('sopir')->group(function () {
    Route::get('home', [DeliveryController::class, 'sopirDelivery']);
    Route::patch('trash/pickup', [DeliveryController::class, 'trashPickup']);
    Route::patch('hadiah/delivery', [DeliveryController::class, 'hadiahDelivery']);
});
