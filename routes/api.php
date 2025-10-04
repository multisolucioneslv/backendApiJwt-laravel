<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SucursaleController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\SexController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return response()->json(['message' => 'Ingresa tus credenciales para continuar']);
});

// Rutas públicas
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Rutas protegidas con JWT
Route::middleware('jwt')->group(function () {
    // Rutas de autenticación
    Route::get('/user', [AuthController::class, 'getUser'])->name('auth.user');
    Route::put('/user', [AuthController::class, 'updateUser'])->name('auth.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    
    // Rutas de recursos con nombres personalizados
    Route::apiResource('sucursales', SucursaleController::class)->names([
        'index' => 'sucursales.index',
        'store' => 'sucursales.store',
        'show' => 'sucursales.show',
        'update' => 'sucursales.update',
        'destroy' => 'sucursales.destroy'
    ]);
    
    Route::apiResource('telegrams', TelegramController::class)->names([
        'index' => 'telegrams.index',
        'store' => 'telegrams.store',
        'show' => 'telegrams.show',
        'update' => 'telegrams.update',
        'destroy' => 'telegrams.destroy'
    ]);
    
    Route::apiResource('phones', PhoneController::class)->names([
        'index' => 'phones.index',
        'store' => 'phones.store',
        'show' => 'phones.show',
        'update' => 'phones.update',
        'destroy' => 'phones.destroy'
    ]);
    
    Route::apiResource('sexs', SexController::class)->names([
        'index' => 'sexs.index',
        'store' => 'sexs.store',
        'show' => 'sexs.show',
        'update' => 'sexs.update',
        'destroy' => 'sexs.destroy'
    ]);
    
    Route::apiResource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'store' => 'categories.store',
        'show' => 'categories.show',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy'
    ]);
    
    Route::apiResource('customers', CustomerController::class)->names([
        'index' => 'customers.index',
        'store' => 'customers.store',
        'show' => 'customers.show',
        'update' => 'customers.update',
        'destroy' => 'customers.destroy'
    ]);
    
    Route::apiResource('productos', ProductoController::class)->names([
        'index' => 'productos.index',
        'store' => 'productos.store',
        'show' => 'productos.show',
        'update' => 'productos.update',
        'destroy' => 'productos.destroy'
    ]);

    Route::apiResource('users', UserController::class)->names([
        'index' => 'users.index',
        'store' => 'users.store',
        'show' => 'users.show',
        'update' => 'users.update',
        'destroy' => 'users.destroy'
    ]);
});