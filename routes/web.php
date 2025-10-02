<?php

use App\Http\Controllers\CampeonatoController;
use App\Http\Controllers\JogadorController;
use App\Http\Controllers\JogoController;
use App\Http\Controllers\StarterController;
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
Route::resource('jogos', JogoController::class);

Route::get('/campeonatos/{campeonato}/starter', [StarterController::class, 'index'])->name('campeonatos.starter');

Route::prefix('campeonatos/{campeonato}')->group(function () {
    Route::get('iniciar', [CampeonatoController::class, 'iniciar'])->name('campeonatos.iniciar');
    Route::put('jogos/{jogo}/save', [JogoController::class, 'update'])->name('jogos.update');
    Route::get('jogos/{jogo}/edit', [JogoController::class, 'edit'])->name('jogos.edit');
    Route::get('jogos', [CampeonatoController::class, 'jogosVisaoGeral'])->name('campeonatos.jogos.visao_geral');

});
