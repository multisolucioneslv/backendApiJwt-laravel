<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSystemAccess;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserSystemAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = UserSystemAccess::with(['user', 'systemType']);

            // Filtros opcionales
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('system_type_id')) {
                $query->where('system_type_id', $request->system_type_id);
            }

            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            if ($request->has('is_admin')) {
                $query->where('is_admin', $request->boolean('is_admin'));
            }

            $accesses = $query->get();

            return response()->json([
                'success' => true,
                'data' => $accesses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar accesos de usuarios',
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
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'system_type_id' => 'required|exists:system_types,id',
                'module_permissions' => 'nullable|array',
                'is_admin' => 'boolean',
                'is_active' => 'boolean',
                'expires_at' => 'nullable|date|after:now'
            ]);

            // Verificar si ya existe un acceso
            $existingAccess = UserSystemAccess::where('user_id', $validated['user_id'])
                ->where('system_type_id', $validated['system_type_id'])
                ->first();

            if ($existingAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario ya tiene acceso a este sistema'
                ], 400);
            }

            $validated['granted_at'] = now();
            $access = UserSystemAccess::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Acceso otorgado exitosamente',
                'data' => $access->load(['user', 'systemType'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al otorgar acceso',
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
            $access = UserSystemAccess::with(['user', 'systemType'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $access
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso no encontrado',
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
            $access = UserSystemAccess::findOrFail($id);

            $validated = $request->validate([
                'module_permissions' => 'nullable|array',
                'is_admin' => 'boolean',
                'is_active' => 'boolean',
                'expires_at' => 'nullable|date|after:now'
            ]);

            $access->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Acceso actualizado exitosamente',
                'data' => $access->load(['user', 'systemType'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar acceso',
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
            $access = UserSystemAccess::findOrFail($id);
            $access->delete();

            return response()->json([
                'success' => true,
                'message' => 'Acceso revocado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al revocar acceso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grant access to a user for a specific system
     */
    public function grantAccess(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'system_type_id' => 'required|exists:system_types,id',
                'module_permissions' => 'nullable|array',
                'is_admin' => 'boolean',
                'expires_at' => 'nullable|date|after:now'
            ]);

            $access = UserSystemAccess::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'system_type_id' => $validated['system_type_id']
                ],
                [
                    'module_permissions' => $validated['module_permissions'] ?? [],
                    'is_admin' => $validated['is_admin'] ?? false,
                    'is_active' => true,
                    'granted_at' => now(),
                    'expires_at' => $validated['expires_at'] ?? null
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Acceso otorgado exitosamente',
                'data' => $access->load(['user', 'systemType'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al otorgar acceso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all accesses for a specific user
     */
    public function getUserAccesses(string $userId): JsonResponse
    {
        try {
            $accesses = UserSystemAccess::with('systemType')
                ->where('user_id', $userId)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $accesses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar accesos del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active status of an access
     */
    public function toggle(string $id): JsonResponse
    {
        try {
            $access = UserSystemAccess::findOrFail($id);
            $access->is_active = !$access->is_active;
            $access->save();

            return response()->json([
                'success' => true,
                'message' => $access->is_active ? 'Acceso activado exitosamente' : 'Acceso desactivado exitosamente',
                'data' => $access->load(['user', 'systemType'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado del acceso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
