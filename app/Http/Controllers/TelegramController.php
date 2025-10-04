<?php

namespace App\Http\Controllers;

use App\Models\Telegram;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TelegramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $telegrams = Telegram::all()->paginate(10);
            return response()->json([
                'message' => 'Telegram IDs obtenidos correctamente',
                'data' => $telegrams,
                'pagination' => [
                    'total' => $telegrams->total(),
                    'per_page' => $telegrams->perPage(),
                    'current_page' => $telegrams->currentPage(),
                    'last_page' => $telegrams->lastPage(),
                    'from' => $telegrams->firstItem(),
                    'to' => $telegrams->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los Telegram IDs: ' . $e->getMessage()
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
                'telegramID' => 'required|string|max:50|unique:telegrams,telegramID'
            ]);

            $telegram = Telegram::create($request->only('telegramID'));

            return response()->json([
                'message' => 'Telegram ID creado correctamente',
                'data' => $telegram
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el Telegram ID: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Telegram $telegram): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Telegram ID obtenido correctamente',
                'data' => $telegram
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el Telegram ID: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Telegram $telegram): JsonResponse
    {
        try {
            $request->validate([
                'telegramID' => 'required|string|max:50|unique:telegrams,telegramID,' . $telegram->id
            ]);

            $telegram->update($request->only('telegramID'));

            return response()->json([
                'message' => 'Telegram ID actualizado correctamente',
                'data' => $telegram
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el Telegram ID: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Telegram $telegram): JsonResponse
    {
        try {
            $telegram->delete();

            return response()->json([
                'message' => 'Telegram ID eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el Telegram ID: ' . $e->getMessage()
            ], 500);
        }
    }
}