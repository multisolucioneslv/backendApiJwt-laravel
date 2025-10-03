<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $customers = Customer::with(['phone', 'telegram', 'user', 'sex'])->get();
            return response()->json([
                'message' => 'Clientes obtenidos correctamente',
                'data' => $customers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los clientes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Si no se proporciona user_id, usar el usuario autenticado
            if (!isset($data['user_id'])) {
                $data['user_id'] = Auth::id();
            }

            $customer = Customer::create($data);

            return response()->json([
                'message' => 'Cliente creado correctamente',
                'data' => $customer->load(['phone', 'telegram', 'user', 'sex'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Cliente obtenido correctamente',
                'data' => $customer->load(['phone', 'telegram', 'user', 'sex'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer): JsonResponse
    {
        try {
            $customer->update($request->validated());

            return response()->json([
                'message' => 'Cliente actualizado correctamente',
                'data' => $customer->load(['phone', 'telegram', 'user', 'sex'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        try {
            $customer->delete();

            return response()->json([
                'message' => 'Cliente eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }
}