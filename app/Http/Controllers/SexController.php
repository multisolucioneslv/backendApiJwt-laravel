<?php

namespace App\Http\Controllers;

use App\Models\Sex;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $sexes = Sex::all();
            return response()->json([
                'message' => 'Sexos obtenidos correctamente',
                'data' => $sexes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los sexos: ' . $e->getMessage()
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
                'name' => 'required|string|max:20',
                'inicial' => 'required|string|max:5'
            ]);

            $sex = Sex::create($request->only(['name', 'inicial']));

            return response()->json([
                'message' => 'Sexo creado correctamente',
                'data' => $sex
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el sexo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sex $sex): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Sexo obtenido correctamente',
                'data' => $sex
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el sexo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sex $sex): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:20',
                'inicial' => 'required|string|max:5'
            ]);

            $sex->update($request->only(['name', 'inicial']));

            return response()->json([
                'message' => 'Sexo actualizado correctamente',
                'data' => $sex
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el sexo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sex $sex): JsonResponse
    {
        try {
            $sex->delete();

            return response()->json([
                'message' => 'Sexo eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el sexo: ' . $e->getMessage()
            ], 500);
        }
    }
}