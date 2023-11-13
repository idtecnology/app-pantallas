<?php

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\TramoController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/p1', function () {
    return view('pantalla1');
})->name('pantalla1');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/p2', function () {
        return view('pantalla2');
    })->name('pantalla2');

    Route::get('/pagar', function () {
        return view('pagar');
    })->name('pagar');


    Route::resource('/tramo', TramoController::class);


    // Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('/sale', MediaController::class);
    Route::get('/sale/programar/{id}', [MediaController::class, 'programar'])->name('sale.programar');


    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('users.profile');


    // Mantenimientos
    Route::resource('/mantenice/roles', RoleController::class);
    Route::resource('/mantenice/users', UserController::class);
    Route::resource('/mantenice/clients', ClientesController::class);
    Route::resource('/mantenice/screen', ScreenController::class);
});
