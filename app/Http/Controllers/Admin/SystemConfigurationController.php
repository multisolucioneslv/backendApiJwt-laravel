<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfiguration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $configurations = SystemConfiguration::with('systemType')->get();
            
            return response()->json([
                'success' => true,
                'data' => $configurations
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'system_type_id' => 'required|exists:system_types,id',
                'key' => 'required|string|max:255',
                'value' => 'required|string',
                'type' => 'required|string|in:string,boolean,integer,json',
                'description' => 'nullable|string',
                'is_required' => 'boolean',
                'is_public' => 'boolean'
            ]);

            $configuration = SystemConfiguration::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Configuración creada exitosamente',
                'data' => $configuration->load('systemType')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear configuración',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $configuration = SystemConfiguration::with('systemType')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $configuration
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Configuración no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $configuration = SystemConfiguration::findOrFail($id);
            
            $request->validate([
                'value' => 'required|string',
                'description' => 'nullable|string',
                'is_required' => 'boolean',
                'is_public' => 'boolean'
            ]);

            $configuration->update($request->only(['value', 'description', 'is_required', 'is_public']));

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada exitosamente',
                'data' => $configuration->load('systemType')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar configuración',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $configuration = SystemConfiguration::findOrFail($id);

            // No permitir eliminar configuraciones requeridas
            if ($configuration->is_required) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar una configuración requerida'
                ], 400);
            }

            $configuration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Configuración eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar configuración',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all configurations for a specific system
     */
    public function getBySystem(Request $request, string $systemTypeId): JsonResponse
    {
        try {
            $query = SystemConfiguration::where('system_type_id', $systemTypeId);

            // Filtros opcionales
            if ($request->has('is_public')) {
                $query->where('is_public', $request->boolean('is_public'));
            }

            if ($request->has('is_required')) {
                $query->where('is_required', $request->boolean('is_required'));
            }

            $configurations = $query->get();

            return response()->json([
                'success' => true,
                'data' => $configurations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar configuraciones del sistema',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public configurations for a specific system
     */
    public function getPublic(string $systemTypeId): JsonResponse
    {
        try {
            $configurations = SystemConfiguration::where('system_type_id', $systemTypeId)
                ->where('is_public', true)
                ->select(['id', 'key', 'value', 'type', 'description'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $configurations
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
     * Update or create configuration by key
     */
    public function updateByKey(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'system_type_id' => 'required|exists:system_types,id',
                'key' => 'required|string|max:255',
                'value' => 'required|string',
                'type' => 'required|string|in:string,boolean,integer,json',
                'description' => 'nullable|string',
                'is_required' => 'boolean',
                'is_public' => 'boolean'
            ]);

            $configuration = SystemConfiguration::updateOrCreate(
                [
                    'system_type_id' => $validated['system_type_id'],
                    'key' => $validated['key']
                ],
                $validated
            );

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada exitosamente',
                'data' => $configuration->load('systemType')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar configuración',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}