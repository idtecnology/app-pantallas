<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\TramoController;
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


Route::post('/tramo', [TramoController::class, 'index']);
Route::post('/availability-dates', [TramoController::class, 'getAvailabilityDates']);
Route::get('/reproducido/{id}', [MediaController::class, 'reproducido']);
Route::get('/disabled-media/{id}', [MediaController::class, 'disabledMedia'])->name('disabled-media');
