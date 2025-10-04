<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Ingresa tus credenciales para continuar']);
    // return view('welcome');
});
