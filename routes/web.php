<?php

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\TramoController;
use App\Http\Controllers\UserController;
use App\Models\Media;
use App\Models\Screen;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
    $screens = Screen::all();
    // return $screens;
    return view('welcome', compact('screens'));
});

Auth::routes();


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/p1/{id}', function ($id) {

    $screen = Screen::find($id);
    return view('pantalla1', compact('id', 'screen'));
})->name('pantalla1');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/p2/{id}/{time}/{media_id}/{preference_id}', function ($id, $time, $media_id, $preference_id) {


        $data = Media::where('_id', '=', $media_id)->get()[0];

        $rutaLocal = 'storage/uploads/tmp/' . $data->files_name;
        // return dd($rutaLocal);

        $url = pathinfo($data->files_name);
        $extension = $url['extension'];
        $extension = strtok($extension, '?');



        return view('pantalla2', compact('id', 'time', 'rutaLocal', 'media_id', 'extension', 'preference_id'));
    })->name('pantalla2');




    Route::post('/guardarData', [MediaController::class, 'guardarData'])->name('guardarData');


    Route::resource('/tramo', TramoController::class);


    // Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('/sale', MediaController::class);
    Route::get('/sale/programar/{id}', [MediaController::class, 'programar'])->name('sale.programar');
    Route::get('/grilla', [MediaController::class, 'grilla'])->name('grilla');
    Route::get('/approved/{id}', [MediaController::class, 'approved'])->name('approved');
    Route::get('/notapproved/{id}', [MediaController::class, 'notApproved'])->name('notapproved');


    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('users.profile');

    Route::post('/stores', [MediaController::class, 'stores'])->name('stores');



    // Mantenimientos
    Route::resource('/mantenice/roles', RoleController::class);
    Route::resource('/mantenice/users', UserController::class);
    Route::resource('/mantenice/clients', ClientesController::class);
    Route::resource('/mantenice/screen', ScreenController::class);


    Route::get('/success', [PagosController::class, 'success'])->name('success');
    Route::get('/pendiente', [PagosController::class, 'pendiente'])->name('pendiente');
    Route::get('/failure', [PagosController::class, 'failure'])->name('failure');
    Route::get('/pagare/{preference}', [PagosController::class, 'crearPago'])->name('pagare');

    Route::resource('/pagos', PagosController::class);
});
