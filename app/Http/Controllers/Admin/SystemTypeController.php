<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $systemTypes = SystemType::with(['modules', 'configurations'])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $systemTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:system_types',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'required_tables' => 'nullable|array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0'
        ]);

        $systemType = SystemType::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tipo de sistema creado exitosamente',
            'data' => $systemType->load(['modules', 'configurations'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemType $systemType): JsonResponse
    {
        $systemType->load(['modules', 'configurations', 'userAccess.user']);

        return response()->json([
            'success' => true,
            'data' => $systemType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SystemType $systemType): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:system_types,slug,' . $systemType->id,
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'required_tables' => 'nullable|array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0'
        ]);

        $systemType->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tipo de sistema actualizado exitosamente',
            'data' => $systemType->load(['modules', 'configurations'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemType $systemType): JsonResponse
    {
        // Verificar si es el sistema por defecto
        if ($systemType->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el sistema por defecto'
            ], 422);
        }

        // Verificar si tiene usuarios asociados
        if ($systemType->userAccess()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un sistema que tiene usuarios asociados'
            ], 422);
        }

        $systemType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de sistema eliminado exitosamente'
        ]);
    }

    /**
     * Activar/desactivar sistema
     */
    public function toggle(SystemType $systemType): JsonResponse
    {
        $systemType->update(['is_active' => !$systemType->is_active]);

        return response()->json([
            'success' => true,
            'message' => $systemType->is_active ? 'Sistema activado' : 'Sistema desactivado',
            'data' => $systemType
        ]);
    }

    /**
     * Establecer como sistema por defecto
     */
    public function setDefault(SystemType $systemType): JsonResponse
    {
        // Desactivar otros sistemas por defecto
        SystemType::where('is_default', true)->update(['is_default' => false]);

        // Establecer este como por defecto
        $systemType->update(['is_default' => true, 'is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Sistema establecido como por defecto',
            'data' => $systemType
        ]);
    }

    /**
     * Obtener sistemas activos para el frontend
     */
    public function active(): JsonResponse
    {
        $systemTypes = SystemType::active()
            ->with(['activeModules', 'publicConfigurations'])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $systemTypes
        ]);
    }

    /**
     * Obtener sistemas activos para setup inicial (público)
     */
    public function getPublicSystemTypes(): JsonResponse
    {
        try {
            $systemTypes = SystemType::active()
                ->select(['id', 'name', 'slug', 'description', 'features', 'required_tables', 'icon', 'color', 'sort_order'])
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $systemTypes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar tipos de sistema',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
