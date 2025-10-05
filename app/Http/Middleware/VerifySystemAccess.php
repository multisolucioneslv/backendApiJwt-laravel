<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserSystemAccess;
use App\Models\SystemType;

class VerifySystemAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $systemTypeSlug  Slug del tipo de sistema (opcional)
     */
    public function handle(Request $request, Closure $next, ?string $systemTypeSlug = null): Response
    {
        // Verificar que el usuario esté autenticado
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $user = $request->user();

        // Obtener el sistema requerido
        $systemType = null;

        if ($systemTypeSlug) {
            // Si se especificó un slug en el middleware
            $systemType = SystemType::where('slug', $systemTypeSlug)->first();
        } elseif ($request->header('X-System-Type')) {
            // Si viene en el header
            $systemType = SystemType::where('slug', $request->header('X-System-Type'))
                ->orWhere('id', $request->header('X-System-Type'))
                ->first();
        } elseif ($request->get('system_type_id')) {
            // Si viene en query params
            $systemType = SystemType::find($request->get('system_type_id'));
        } else {
            // Usar el sistema por defecto
            $systemType = SystemType::where('is_default', true)->first();
        }

        // Si no se encontró el sistema
        if (!$systemType) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de sistema no encontrado'
            ], 404);
        }

        // Verificar si el sistema está activo
        if (!$systemType->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'El sistema no está activo actualmente'
            ], 403);
        }

        // Verificar acceso del usuario al sistema
        $access = UserSystemAccess::where('user_id', $user->id)
            ->where('system_type_id', $systemType->id)
            ->first();

        // Si no tiene acceso registrado y es sistema por defecto, permitir acceso
        if (!$access && $systemType->is_default) {
            // Agregar información del sistema al request
            $request->merge([
                'current_system_type' => $systemType,
                'current_system_access' => null,
                'is_system_admin' => false
            ]);

            return $next($request);
        }

        // Si no tiene acceso
        if (!$access) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene acceso a este sistema'
            ], 403);
        }

        // Verificar que el acceso esté activo
        if (!$access->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Su acceso a este sistema está desactivado'
            ], 403);
        }

        // Verificar que el acceso no haya expirado
        if ($access->expires_at && $access->expires_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Su acceso a este sistema ha expirado'
            ], 403);
        }

        // Agregar información del sistema y acceso al request
        $request->merge([
            'current_system_type' => $systemType,
            'current_system_access' => $access,
            'is_system_admin' => $access->is_admin,
            'module_permissions' => $access->module_permissions ?? []
        ]);

        return $next($request);
    }
}
