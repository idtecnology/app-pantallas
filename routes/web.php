<?php

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\TramoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Models\Screen;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Enlace de verificación enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $r) {
    $r->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::get('/', function () {
    $screens = Screen::all();
    return view('welcome', compact('screens'));
});

Route::get('/preguntas-frecuentes', function () {
    return view('layouts.faq');
})->name('faq');


Auth::routes();

Route::post('/forgot-password', [ClientesController::class, 'olvidoPassword'])->name('forgot-password');


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/p1/{id}', [ScreenController::class, 'screenUno'])->name('pantalla1');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('/p2', [ScreenController::class, 'screenDos']);

    Route::resource('/tramo', TramoController::class);

    // Media
    Route::resource('/sale', MediaController::class);
    Route::get('/sale/programar/{id}', [MediaController::class, 'programar'])->name('sale.programar');
    Route::get('/programacion', [MediaController::class, 'grilla'])->name('grilla');
    Route::get('/approved/{id}', [MediaController::class, 'approved'])->name('approved');
    Route::get('/send-mail', [MediaController::class, 'send'])->name('send');
    Route::get('/notapproved/{id}', [MediaController::class, 'notApproved'])->name('notapproved');
    Route::post('/guardarData', [MediaController::class, 'guardarData'])->name('guardarData');
    Route::post('/sale/store-massive', [MediaController::class, 'storeMassive'])->name('sale.store-massive');

    //Perfil
    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('users.profile');

    // Mantenimientos
    Route::resource('/mantenice/roles', RoleController::class);
    Route::resource('/mantenice/users', UserController::class);
    Route::resource('/mantenice/clients', ClientesController::class);
    Route::resource('/mantenice/screen', ScreenController::class);

    Route::post('/mantenice/clients/eliminar', [ClientesController::class, 'eliminar']);

    //Pagos
    Route::resource('/pagos', PagosController::class);
    Route::get('/success', [PagosController::class, 'success'])->name('success');
    Route::get('/succes', [PagosController::class, 'succes'])->name('succes');
    Route::get('/pendiente', [PagosController::class, 'pendiente'])->name('pendiente');
    Route::get('/failure', [PagosController::class, 'failure'])->name('failure');
    Route::get('/pagare/{preference}', [PagosController::class, 'crearPago'])->name('pagare');
});
