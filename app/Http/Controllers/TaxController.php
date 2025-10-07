<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TaxRequest;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $taxes = Tax::all();
            return response()->json([
                'message' => 'Impuestos obtenidos correctamente',
                'data' => $taxes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los impuestos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the active tax.
     */
    public function getActive(): JsonResponse
    {
        try {
            $tax = Tax::where('is_active', true)->first();

            if (!$tax) {
                return response()->json([
                    'message' => 'No hay impuesto activo',
                    'data' => null
                ]);
            }

            return response()->json([
                'message' => 'Impuesto activo obtenido correctamente',
                'data' => $tax
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el impuesto activo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxRequest $request): JsonResponse
    {
        try {
            $tax = Tax::create($request->validated());

            return response()->json([
                'message' => 'Impuesto creado correctamente',
                'data' => $tax
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el impuesto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tax $tax): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Impuesto obtenido correctamente',
                'data' => $tax
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el impuesto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxRequest $request, Tax $tax): JsonResponse
    {
        try {
            $tax->update($request->validated());

            return response()->json([
                'message' => 'Impuesto actualizado correctamente',
                'data' => $tax
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el impuesto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax): JsonResponse
    {
        try {
            $tax->delete();

            return response()->json([
                'message' => 'Impuesto eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el impuesto: ' . $e->getMessage()
            ], 500);
        }
    }
}
