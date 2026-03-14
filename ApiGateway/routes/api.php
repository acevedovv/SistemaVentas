
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VentasController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/ventas', [VentasController::class, 'registrarVenta']);
    Route::get('/ventas', [VentasController::class, 'consultarVentas']);
    Route::get('/ventas/fecha/{fecha}', [VentasController::class, 'consultarVentasPorFecha']);
});