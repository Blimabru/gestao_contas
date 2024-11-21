<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchCepController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinancesController;
use App\Models\Settings;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/settings', function () {
    return response()->json(Settings::first());
});

Route::post('/settings/update', function () {
    return response()->json(Settings::first());
});

Route::match(['get', 'post'], '/consulta-cep', [SearchCepController::class, 'consultaCep']);

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Route::middleware('auth')->group(function () {
    Route::get('/finances', [FinancesController::class, 'index']);
    Route::post('/finances', [FinancesController::class, 'store']);
    Route::put('/finances/{finance}', [FinancesController::class, 'update']);
    Route::delete('/finances/{finance}', [FinancesController::class, 'destroy']);
// });
