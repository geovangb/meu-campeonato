<?php

use App\Http\Controllers\Api\JogoApiController;
use App\Http\Controllers\Api\TimeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\Api\CampeonatoStarterApiController;

Route::prefix('campeonatos/{campeonato}/starter')->group(function () {
    Route::post('times', [CampeonatoStarterApiController::class, 'salvarTimes']);
    Route::post('regras', [CampeonatoStarterApiController::class, 'salvarRegras']);
    Route::post('sorteios', [CampeonatoStarterApiController::class, 'salvarSorteios']);
    Route::post('sortear', [CampeonatoStarterApiController::class, 'sortear']);
    Route::post('salvarSorteios', [CampeonatoStarterApiController::class, 'salvarSorteios']);
    Route::get('status', [CampeonatoStarterApiController::class, 'status']);


});

Route::post('/times', [TimeController::class, 'store']);

Route::post('/jogos/{jogo}/update-date', [JogoApiController::class, 'updateDate']);
Route::post('/jogos/bulk-update-dates', [JogoApiController::class, 'bulkUpdateDates']);

