<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $phones = Phone::all();
            return response()->json([
                'message' => 'Teléfonos obtenidos correctamente',
                'data' => $phones
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los teléfonos: ' . $e->getMessage()
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
                'phone' => 'required|string|max:20|unique:phones,phone'
            ]);

            $phone = Phone::create($request->only('phone'));

            return response()->json([
                'message' => 'Teléfono creado correctamente',
                'data' => $phone
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el teléfono: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Phone $phone): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Teléfono obtenido correctamente',
                'data' => $phone
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el teléfono: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phone $phone): JsonResponse
    {
        try {
            $request->validate([
                'phone' => 'required|string|max:20|unique:phones,phone,' . $phone->id
            ]);

            $phone->update($request->only('phone'));

            return response()->json([
                'message' => 'Teléfono actualizado correctamente',
                'data' => $phone
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el teléfono: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phone $phone): JsonResponse
    {
        try {
            $phone->delete();

            return response()->json([
                'message' => 'Teléfono eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el teléfono: ' . $e->getMessage()
            ], 500);
        }
    }
}