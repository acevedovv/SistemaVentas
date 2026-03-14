<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VentasController extends Controller
{
    private $flaskUrl = 'http://localhost:5000';
    private $expressUrl = 'http://localhost:3000';

    public function registrarVenta(Request $request)
{
    $data = $request->validate([
        'producto_id'     => 'required|string',
        'nombre_producto' => 'required|string',
        'cantidad'        => 'required|integer|min:1',
        'precio_unitario' => 'required|numeric',
    ]);

    // Paso 1: Verificar stock en Flask
    $stockResponse = Http::get("{$this->flaskUrl}/productos/verificar-stock/{$data['producto_id']}", [
        'cantidad' => $data['cantidad']
    ]);

    if (!$stockResponse->ok()) {
        return response()->json(['error' => 'Producto no encontrado', 'detalle' => $stockResponse->body()], 404);
    }

    $stock = $stockResponse->json();

    if (!$stock['disponible']) {
        return response()->json([
            'error' => 'Stock insuficiente',
            'stock_disponible' => $stock['stock']
        ], 400);
    }

    // Paso 2: Registrar venta en Express
    $usuario = auth()->user();
    $ventaResponse = Http::post("{$this->expressUrl}/ventas", [
        'usuario_id'      => $usuario->id,
        'producto_id'     => $data['producto_id'],
        'nombre_producto' => $data['nombre_producto'],
        'cantidad'        => $data['cantidad'],
        'precio_unitario' => $data['precio_unitario'],
        'total'           => $data['cantidad'] * $data['precio_unitario'],
    ]);

    if (!$ventaResponse->successful()) {
        return response()->json([
            'error' => 'Error al registrar la venta',
            'detalle' => $ventaResponse->body(),
            'status' => $ventaResponse->status()
        ], 500);
    }

    // Paso 3: Actualizar stock en Flask
    Http::put("{$this->flaskUrl}/productos/actualizar-stock/{$data['producto_id']}", [
        'cantidad' => $data['cantidad']
    ]);

    return response()->json([
        'mensaje' => 'Venta registrada exitosamente',
        'venta'   => $ventaResponse->json()
    ], 201);
}

    public function consultarVentas()
    {
        $response = Http::get("{$this->expressUrl}/ventas");
        return response()->json($response->json());
    }

    public function consultarVentasPorFecha($fecha)
    {
        $response = Http::get("{$this->expressUrl}/ventas/fecha/{$fecha}");
        return response()->json($response->json());
    }
}