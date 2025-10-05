<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemModule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $modules = SystemModule::with('systemType')->get();
            
            return response()->json([
                'success' => true,
                'data' => $modules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar módulos',
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
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'nullable|string',
                'route' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:7',
                'permissions' => 'nullable|array',
                'is_active' => 'boolean',
                'is_required' => 'boolean',
                'sort_order' => 'nullable|integer'
            ]);

            $module = SystemModule::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Módulo creado exitosamente',
                'data' => $module->load('systemType')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear módulo',
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
            $module = SystemModule::with('systemType')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $module
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Módulo no encontrado',
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
            $module = SystemModule::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'nullable|string',
                'route' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:7',
                'permissions' => 'nullable|array',
                'is_active' => 'boolean',
                'is_required' => 'boolean',
                'sort_order' => 'nullable|integer'
            ]);

            $module->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Módulo actualizado exitosamente',
                'data' => $module->load('systemType')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar módulo',
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
            $module = SystemModule::findOrFail($id);
            
            // No permitir eliminar módulos requeridos
            if ($module->is_required) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar un módulo requerido'
                ], 400);
            }
            
            $module->delete();

            return response()->json([
                'success' => true,
                'message' => 'Módulo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar módulo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get modules by system type
     */
    public function getBySystemType(string $systemTypeId): JsonResponse
    {
        try {
            $modules = SystemModule::where('system_type_id', $systemTypeId)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $modules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar módulos del sistema',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public modules by system type for initial setup
     */
    public function getPublicModulesBySystemType(string $systemTypeId): JsonResponse
    {
        try {
            $modules = SystemModule::where('system_type_id', $systemTypeId)
                ->where('is_active', true)
                ->select(['id', 'name', 'slug', 'description', 'icon', 'color', 'is_required', 'sort_order'])
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $modules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar módulos para el tipo de sistema',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle module active status
     */
    public function toggle(string $id): JsonResponse
    {
        try {
            $module = SystemModule::findOrFail($id);

            // No permitir desactivar módulos requeridos
            if ($module->is_required && $module->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desactivar un módulo requerido'
                ], 400);
            }

            $module->is_active = !$module->is_active;
            $module->save();

            return response()->json([
                'success' => true,
                'message' => $module->is_active ? 'Módulo activado exitosamente' : 'Módulo desactivado exitosamente',
                'data' => $module->load('systemType')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado del módulo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}