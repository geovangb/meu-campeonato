<?php

use App\Http\Controllers\CampeonatoController;
use App\Http\Controllers\JogadorController;
use App\Http\Controllers\TimeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('campeonatos', CampeonatoController::class);
Route::resource('times', TimeController::class);
Route::resource('jogadores', JogadorController::class);
Route::get('/campeonatos/{campeonato}/starter', [CampeonatoStarterController::class, 'index'])->name('campeonatos.starter');



