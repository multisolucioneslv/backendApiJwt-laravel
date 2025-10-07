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
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\Admin\SystemTypeController;
use App\Http\Controllers\Admin\SystemModuleController;
use App\Http\Controllers\Admin\UserSystemAccessController;
use App\Http\Controllers\Admin\SystemConfigurationController;
use App\Http\Controllers\InitialConfigurationController;
use App\Http\Controllers\DateTimeController;
use App\Http\Controllers\TaxController;

Route::get('/', function () {
    return response()->json(['message' => 'Ingresa tus credenciales para continuar']);
});

// Rutas públicas
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Rutas de configuración inicial (públicas)
Route::get('/initial-config/status', [InitialConfigurationController::class, 'checkStatus'])->name('initial-config.status');
Route::get('/initial-config/public', [InitialConfigurationController::class, 'getPublicConfigurations'])->name('initial-config.public');
Route::post('/initial-config/mark-completed', [InitialConfigurationController::class, 'markAsCompleted'])->name('initial-config.mark-completed');
Route::post('/initial-config/reset', [InitialConfigurationController::class, 'reset'])->name('initial-config.reset');

// Rutas de fecha y hora
Route::get('/datetime/current', [DateTimeController::class, 'getCurrentDateTime'])->name('datetime.current');
Route::post('/reports/create', [DateTimeController::class, 'createReport'])->name('reports.create');

// Rutas de configuración de zona horaria
Route::get('/timezone/current', [DateTimeController::class, 'getTimezone'])->name('timezone.current');
Route::post('/timezone/set', [DateTimeController::class, 'setTimezone'])->name('timezone.set');
Route::get('/timezone/available', [DateTimeController::class, 'getAvailableTimezones'])->name('timezone.available');

// Rutas públicas para setup inicial
Route::get('/system-types/public', [SystemTypeController::class, 'getPublicSystemTypes'])->name('system-types.public');
Route::get('/system-modules/public/{systemTypeId}', [SystemModuleController::class, 'getPublicModulesBySystemType'])->name('system-modules.public');

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

    // Rutas específicas de cotizaciones ANTES del apiResource
    Route::get('cotizaciones/estadisticas', [CotizacionController::class, 'estadisticas'])->name('cotizaciones.estadisticas');
    Route::post('cotizaciones/bulk-delete', [CotizacionController::class, 'bulkDelete'])->name('cotizaciones.bulk-delete');
    Route::patch('cotizaciones/{cotizacion}/estado', [CotizacionController::class, 'actualizarEstado'])->name('cotizaciones.actualizar-estado');

    Route::apiResource('cotizaciones', CotizacionController::class)->names([
        'index' => 'cotizaciones.index',
        'store' => 'cotizaciones.store',
        'show' => 'cotizaciones.show',
        'update' => 'cotizaciones.update',
        'destroy' => 'cotizaciones.destroy'
    ]);

    // Rutas para impuestos
    Route::get('taxes/active', [TaxController::class, 'getActive'])->name('taxes.active');
    Route::apiResource('taxes', TaxController::class)->names([
        'index' => 'taxes.index',
        'store' => 'taxes.store',
        'show' => 'taxes.show',
        'update' => 'taxes.update',
        'destroy' => 'taxes.destroy'
    ]);

    // Rutas de administración del sistema multi-servicio
    Route::prefix('admin')->group(function () {
        // Rutas de configuración inicial (solo admin)
        Route::apiResource('initial-configurations', InitialConfigurationController::class)->names([
            'index' => 'admin.initial-configurations.index',
            'store' => 'admin.initial-configurations.store',
            'show' => 'admin.initial-configurations.show',
            'update' => 'admin.initial-configurations.update',
            'destroy' => 'admin.initial-configurations.destroy'
        ]);
        
        // Rutas de tipos de sistema
        Route::apiResource('system-types', SystemTypeController::class)->names([
            'index' => 'admin.system-types.index',
            'store' => 'admin.system-types.store',
            'show' => 'admin.system-types.show',
            'update' => 'admin.system-types.update',
            'destroy' => 'admin.system-types.destroy'
        ]);
        
        Route::post('system-types/{systemType}/toggle', [SystemTypeController::class, 'toggle'])->name('admin.system-types.toggle');
        Route::post('system-types/{systemType}/set-default', [SystemTypeController::class, 'setDefault'])->name('admin.system-types.set-default');
        Route::get('system-types-active', [SystemTypeController::class, 'active'])->name('admin.system-types.active');

        // Rutas de módulos del sistema
        Route::apiResource('system-modules', SystemModuleController::class)->names([
            'index' => 'admin.system-modules.index',
            'store' => 'admin.system-modules.store',
            'show' => 'admin.system-modules.show',
            'update' => 'admin.system-modules.update',
            'destroy' => 'admin.system-modules.destroy'
        ]);
        
        Route::get('system-modules/by-system-type/{systemTypeId}', [SystemModuleController::class, 'getBySystemType'])->name('admin.system-modules.by-system-type');

        // Rutas de acceso de usuarios
        Route::apiResource('user-system-access', UserSystemAccessController::class)->names([
            'index' => 'admin.user-system-access.index',
            'store' => 'admin.user-system-access.store',
            'show' => 'admin.user-system-access.show',
            'update' => 'admin.user-system-access.update',
            'destroy' => 'admin.user-system-access.destroy'
        ]);

        // Rutas de configuraciones del sistema
        Route::apiResource('system-configurations', SystemConfigurationController::class)->names([
            'index' => 'admin.system-configurations.index',
            'store' => 'admin.system-configurations.store',
            'show' => 'admin.system-configurations.show',
            'update' => 'admin.system-configurations.update',
            'destroy' => 'admin.system-configurations.destroy'
        ]);
    });
});