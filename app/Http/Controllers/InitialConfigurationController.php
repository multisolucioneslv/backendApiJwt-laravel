<?php

namespace App\Http\Controllers;

use App\Models\InitialConfiguration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InitialConfigurationController extends Controller
{
    /**
     * Verificar el estado de la configuración inicial
     */
    public function checkStatus(): JsonResponse
    {
        try {
            $isCompleted = InitialConfiguration::getByKey('initial_setup_completed', false);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'is_completed' => $isCompleted,
                    'message' => $isCompleted ? 'Configuración inicial completada' : 'Configuración inicial pendiente'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar estado de configuración inicial',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las configuraciones públicas
     */
    public function getPublicConfigurations(): JsonResponse
    {
        try {
            $configurations = InitialConfiguration::public()->get();
            
            $formattedConfigs = $configurations->map(function ($config) {
                return [
                    'key' => $config->key,
                    'value' => $config->converted_value,
                    'type' => $config->type,
                    'description' => $config->description
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedConfigs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar configuraciones públicas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las configuraciones (solo admin)
     */
    public function index(): JsonResponse
    {
        try {
            $configurations = InitialConfiguration::all();
            
            $formattedConfigs = $configurations->map(function ($config) {
                return [
                    'id' => $config->id,
                    'key' => $config->key,
                    'value' => $config->converted_value,
                    'type' => $config->type,
                    'description' => $config->description,
                    'is_required' => $config->is_required,
                    'is_public' => $config->is_public,
                    'created_at' => $config->created_at,
                    'updated_at' => $config->updated_at
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedConfigs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar configuraciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear o actualizar una configuración
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'key' => 'required|string|max:255',
                'value' => 'required',
                'type' => 'required|string|in:string,boolean,integer,json',
                'description' => 'nullable|string',
                'is_required' => 'boolean',
                'is_public' => 'boolean'
            ]);

            $config = InitialConfiguration::setByKey(
                $request->key,
                $request->value,
                $request->type,
                $request->description
            );

            if ($request->has('is_required')) {
                $config->is_required = $request->is_required;
            }
            if ($request->has('is_public')) {
                $config->is_public = $request->is_public;
            }
            $config->save();

            return response()->json([
                'success' => true,
                'message' => 'Configuración guardada exitosamente',
                'data' => [
                    'id' => $config->id,
                    'key' => $config->key,
                    'value' => $config->converted_value,
                    'type' => $config->type
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar configuración',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar configuración inicial como completada
     */
    public function markAsCompleted(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'system_type' => 'required|string',
                'admin_data' => 'required|array',
                'modules' => 'nullable|array'
            ]);

            // Marcar como completada
            InitialConfiguration::setByKey(
                'initial_setup_completed',
                true,
                'boolean',
                'Indica si la configuración inicial del sistema está completada'
            );

            // Guardar tipo de sistema seleccionado
            InitialConfiguration::setByKey(
                'selected_system_type',
                $request->system_type,
                'json',
                'Tipo de sistema seleccionado durante la configuración inicial'
            );

            // Guardar datos del administrador
            InitialConfiguration::setByKey(
                'admin_data',
                $request->admin_data,
                'json',
                'Datos del administrador creado durante la configuración inicial'
            );

            // Guardar módulos seleccionados
            if ($request->has('modules')) {
                InitialConfiguration::setByKey(
                    'selected_modules',
                    $request->modules,
                    'json',
                    'Módulos seleccionados durante la configuración inicial'
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Configuración inicial marcada como completada',
                'data' => [
                    'is_completed' => true,
                    'system_type' => $request->system_type,
                    'admin_data' => $request->admin_data
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar configuración como completada',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resetear configuración inicial
     */
    public function reset(): JsonResponse
    {
        try {
            // Eliminar todas las configuraciones relacionadas con setup inicial
            InitialConfiguration::whereIn('key', [
                'initial_setup_completed',
                'selected_system_type',
                'admin_data',
                'selected_modules'
            ])->delete();

            return response()->json([
                'success' => true,
                'message' => 'Configuración inicial reseteada exitosamente',
                'data' => [
                    'is_completed' => false
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al resetear configuración inicial',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
