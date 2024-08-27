<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\ProgramContentController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\QuestionerController;
use App\Http\Controllers\ServerEnvironmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::view('/', "index")->name("home");

Route::middleware('json.request')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('auth.admin')->prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::get('/form/{user}', [UserController::class, 'detailForm']);
    });

    Route::prefix('questioners')->group(function () {
        Route::middleware('auth.user_or_driver')->group(function () {
            Route::get('/unanswered', [QuestionerController::class, 'getUnansweredQuestions']);
            Route::post('/answer', [QuestionerController::class, 'submitAnswer']);
        });

        Route::middleware('auth.admin')->group(function () {
            Route::post('/', [QuestionerController::class, 'store']);
            Route::get('/', [QuestionerController::class, 'index']);
            Route::get('/{questioner}', [QuestionerController::class, 'show']);
            Route::put('/{questioner}', [QuestionerController::class, 'update']);
        });
    });


    Route::prefix('educations')->group(function () {
        Route::middleware('auth.any')->group(function () {
            Route::get('/', [EducationController::class, 'index']);
        });
        Route::middleware('auth.admin')->group(function () {
            Route::get('/{education}', [EducationController::class, 'show']);
            Route::post('/', [EducationController::class, 'store']);
            Route::post('/{education}', [EducationController::class, 'update']);
            Route::delete('/{education}', [EducationController::class, 'destroy']);
        });
    });

    Route::prefix('programs')->group(function () {
        Route::middleware('auth.any')->group(function () {
            Route::get('/', [ProgramController::class, 'index']);
            Route::get('/{program}', [ProgramController::class, 'show']);
            Route::prefix('/{program}/contents')->group(function () {
                Route::get('/', [ProgramContentController::class, 'index']);
            });
        });

        Route::middleware('auth.admin')->group(function () {
            Route::post('/', [ProgramController::class, 'store']);
            Route::post('/{program}', [ProgramController::class, 'update']);
            Route::delete('/{program}', [ProgramController::class, 'destroy']);

            Route::prefix('/{program}/contents')->group(function () {
                Route::get('/{programContent}', [ProgramContentController::class, 'show']);
                Route::post('/', [ProgramContentController::class, 'store']);
                Route::post('/{programContent}', [ProgramContentController::class, 'update']);
                Route::delete('/{programContent}', [ProgramContentController::class, 'destroy']);
            });
        });
    });

    Route::middleware('auth.admin')->prefix('prizes')->group(function () {
        Route::get('/', [PrizeController::class, 'index']);
        Route::post('/', [PrizeController::class, 'store']);
        Route::get('/{prize}', [PrizeController::class, 'show']);
        Route::post('/{prize}', [PrizeController::class, 'update']);
        Route::delete('/{prize}', [PrizeController::class, 'destroy']);
        Route::put('/batch-update', [PrizeController::class, 'batchUpdate']);
    });

    Route::prefix('server-environment')->group(function () {
        Route::middleware('auth.any')->group(function () {
            Route::get('/', [ServerEnvironmentController::class, 'index']);
        });

        Route::middleware('auth.admin')->group(function () {
            Route::put('/', [ServerEnvironmentController::class, 'update']);
        });

    });
});

Route::get('/storage/images/{filename}', function ($filename) {
    storage_path('app/public/images/' . $filename);

    if (!Storage::disk('public')->exists('images/' . $filename)) {
        abort(404);
    }

    $file = Storage::disk('public')->get('images/' . $filename);
    $type = Storage::disk('public')->mimeType('images/' . $filename);

    return response($file, 200)->header('Content-Type', $type);
});
