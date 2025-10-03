<?php

namespace App\Http\Controllers;

use App\Models\Sucursale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class SucursaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $sucursales = Sucursale::with('user')->get();
            return response()->json([
                'message' => 'Sucursales obtenidas correctamente',
                'data' => $sucursales
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener las sucursales: ' . $e->getMessage()
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
                'nombre' => 'required|string|max:50',
                'direccion' => 'required|string|max:100',
                'activo' => 'sometimes|boolean',
                'principal' => 'sometimes|boolean',
                'user_id' => 'sometimes|integer|exists:users,id'
            ]);

            $data = $request->only(['nombre', 'direccion', 'activo', 'principal', 'user_id']);
            
            // Si no se proporciona user_id, usar el usuario autenticado
            if (!isset($data['user_id'])) {
                $data['user_id'] = Auth::id();
            }

            $sucursale = Sucursale::create($data);

            return response()->json([
                'message' => 'Sucursal creada correctamente',
                'data' => $sucursale->load('user')
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear la sucursal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sucursale $sucursale): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Sucursal obtenida correctamente',
                'data' => $sucursale->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la sucursal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sucursale $sucursale): JsonResponse
    {
        try {
            $request->validate([
                'nombre' => 'sometimes|required|string|max:50',
                'direccion' => 'sometimes|required|string|max:100',
                'activo' => 'sometimes|boolean',
                'principal' => 'sometimes|boolean',
                'user_id' => 'sometimes|integer|exists:users,id'
            ]);

            $sucursale->update($request->only(['nombre', 'direccion', 'activo', 'principal', 'user_id']));

            return response()->json([
                'message' => 'Sucursal actualizada correctamente',
                'data' => $sucursale->load('user')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar la sucursal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sucursale $sucursale): JsonResponse
    {
        try {
            $sucursale->delete();

            return response()->json([
                'message' => 'Sucursal eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la sucursal: ' . $e->getMessage()
            ], 500);
        }
    }
}