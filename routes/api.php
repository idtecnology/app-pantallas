<?php

use App\Http\Controllers\API\{AuthController, TramoController, MediaController, ScreenController};
use App\Http\Controllers\{TramoController as TC, MediaController as MC};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(
    function () {
        Route::post('/tramo', [TC::class, 'index']);
        Route::post('/availability-dates', [TC::class, 'getAvailabilityDates']);
        Route::get('/reproducido/{id}', [MC::class, 'reproducido']);
        Route::get('/disabled-media/{id}', [MC::class, 'disabledMedia'])->name('disabled-media');
        Route::get('/select-screen/{id}', [MC::class, 'selectScreen'])->name('select-screen');

        Route::post('/search-programation', [MC::class, 'searchProgramation'])->name('search-programation');
        Route::post('/guardarData', [MC::class, 'guardarData'])->name('guardar-data');
    }
);


Route::prefix('v2')->group(
    function () {

        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::get('/logout', [AuthController::class, 'logout']);

            //Tramos routes
            Route::prefix('tramos')->group(function () {
                Route::post('/', [TramoController::class, 'index']);
                Route::post('/availability-dates', [TramoController::class, 'getAvailabilityDates']);
            });

            //Screen routes
            Route::prefix('screen')->group(function () {
                Route::get('/', [ScreenController::class, 'getScreens']);
                Route::get('/{id}', [ScreenController::class, 'getScreen']);
            });


            //Media routes
            Route::prefix('media')->group(function () {
                Route::get('/', [MediaController::class, 'index']);
                Route::get('/disabled/{id}', [MediaController::class, 'disabledMedia']);
                Route::get('/approved/{id}', [MediaController::class, 'approved']);
                Route::get('/not-approved/{id}', [MediaController::class, 'notApproved']);
                Route::get('/reproducido/{id}', [MediaController::class, 'reproducido']);
                Route::get('/show/{id}', [MediaController::class, 'show']);
                Route::post('/store-camping', [MediaController::class, 'storeMassive']);
                Route::post('/presave-media', [MediaController::class, 'guardarData']);
                Route::post('/search-programation', [MediaController::class, 'searchProgramation']);
                Route::post('/store', [MediaController::class, 'store']);
            });
        });
    }
);
