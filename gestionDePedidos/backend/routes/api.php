<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuariosController;
use App\Http\Controllers\Api\ProductosController;
use App\Http\Controllers\Api\DireccionesController;
use App\Http\Controllers\Api\PedidosController;
use App\Http\Controllers\Api\AuthController;


// Rutas para Usuarios
Route::get('/usuarios', [UsuariosController::class, 'index']);
Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);
Route::post('/usuarios', [UsuariosController::class, 'store']);
Route::put('/usuarios/{id}', [UsuariosController::class, 'update']);
Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy']);

//Login
Route::post('/login', [AuthController::class, 'login']);


// Rutas para Productos
Route::get('/productos', [ProductosController::class, 'index']);
Route::get('/productos/{id}', [ProductosController::class, 'show']);
Route::post('/productos', [ProductosController::class, 'store']);
Route::put('/productos/{id}', [ProductosController::class, 'update']);
Route::delete('/productos/{id}', [ProductosController::class, 'destroy']);

// Rutas para direcciones

Route::get('/direcciones', [DireccionesController::class, 'index']); 
Route::post('/direcciones', [DireccionesController::class, 'store']); 
Route::delete('/direcciones/{id}', [DireccionesController::class, 'destroy']);

// Rutas para pedidos
Route::get('/pedidos', [PedidosController::class, 'index']); 
Route::get('/pedidos/{id}', [PedidosController::class, 'show']); 
Route::post('/pedidos', [PedidosController::class, 'store']); 
Route::put('/pedidos/{id}', [PedidosController::class, 'update']); 
Route::delete('/pedidos/{id}', [PedidosController::class, 'destroy']); 
    
