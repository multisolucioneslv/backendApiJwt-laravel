<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    /**
     * Listar todas las cotizaciones
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Cotizacion::with(['customer', 'user', 'items.producto']);

            // Filtros
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_cotizacion', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_cotizacion', '<=', $request->fecha_hasta);
            }

            // Ordenamiento
            $ordenarPor = $request->get('sort_by', 'created_at');
            $ordenDireccion = $request->get('sort_direction', 'desc');

            $cotizaciones = $query->orderBy($ordenarPor, $ordenDireccion)->paginate(10);

            return response()->json([
                'mensaje' => 'Cotizaciones obtenidas correctamente',
                'data' => $cotizaciones,
                'paginacion' => [
                    'total' => $cotizaciones->total(),
                    'por_pagina' => $cotizaciones->perPage(),
                    'pagina_actual' => $cotizaciones->currentPage(),
                    'ultima_pagina' => $cotizaciones->lastPage(),
                    'desde' => $cotizaciones->firstItem(),
                    'hasta' => $cotizaciones->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener las cotizaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva cotización
     */
    public function store(Request $request): JsonResponse
    {
        try {
            \Log::info('Datos recibidos en store:', $request->all());
            
            // Validación dinámica: customer_id o nuevo_cliente
            $validated = $request->validate([
                'customer_id' => 'nullable|exists:customers,id',
                'nuevo_cliente' => 'nullable|array',
                'nuevo_cliente.name' => 'required_with:nuevo_cliente|string|max:50',
                'nuevo_cliente.lastname' => 'required_with:nuevo_cliente|string|max:50',
                'nuevo_cliente.email' => 'nullable|email',
                'nuevo_cliente.phone' => 'nullable|string',
                'nuevo_cliente.company' => 'nullable|string',
                'nuevo_cliente.address' => 'nullable|string',
                'estado' => 'nullable|in:generada,entregada,vendida,rechazada',
                'impuesto' => 'nullable|numeric|min:0',
                'descuento' => 'nullable|numeric|min:0',
                'notas' => 'nullable|string',
                'terminos' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.producto_id' => 'required|exists:productos,id',
                'items.*.cantidad' => 'required|integer|min:1',
                'items.*.precio_unitario' => 'required|numeric|min:0',
                'items.*.descuento' => 'nullable|numeric|min:0',
                'items.*.descripcion' => 'nullable|string',
            ]);

            \Log::info('Datos validados:', $validated);

            // Validar que se proporcione customer_id o nuevo_cliente
            if (!$validated['customer_id'] && !isset($validated['nuevo_cliente'])) {
                return response()->json([
                    'error' => 'Debe proporcionar un cliente existente o datos para un nuevo cliente'
                ], 422);
            }

            DB::beginTransaction();

            // Si es un nuevo cliente, crearlo primero
            $customerId = $validated['customer_id'];
            
            if (isset($validated['nuevo_cliente'])) {
                // Crear o encontrar el teléfono si se proporciona
                $phoneId = 1; // Default
                if (!empty($validated['nuevo_cliente']['phone'])) {
                    $phone = Phone::firstOrCreate(
                        ['phone' => $validated['nuevo_cliente']['phone']]
                    );
                    $phoneId = $phone->id;
                }
                
                $nuevoCliente = Customer::create([
                    'name' => $validated['nuevo_cliente']['name'],
                    'lastname' => $validated['nuevo_cliente']['lastname'],
                    'email' => $validated['nuevo_cliente']['email'] ?? null,
                    'phone_id' => $phoneId,
                    'telegram_id' => 1, // Default telegram
                    'user_id' => Auth::id(),
                    'sex_id' => 1, // Default sex
                    'address' => $validated['nuevo_cliente']['address'] ?? null,
                ]);
                
                $customerId = $nuevoCliente->id;
            }

            // Crear la cotización
            $cotizacion = Cotizacion::create([
                'numero_cotizacion' => Cotizacion::generarNumeroCotizacion(),
                'customer_id' => $customerId,
                'user_id' => Auth::id(),
                'fecha_cotizacion' => now()->toDateString(),
                'fecha_vencimiento' => now()->addDays(30)->toDateString(),
                'estado' => $validated['estado'] ?? 'generada',
                'impuesto' => $validated['impuesto'] ?? 0,
                'descuento' => $validated['descuento'] ?? 0,
                'notas' => $validated['notas'] ?? null,
                'terminos' => $validated['terminos'] ?? null,
            ]);

            // Crear los items
            $subtotal = 0;
            foreach ($validated['items'] as $itemData) {
                $itemSubtotal = ($itemData['cantidad'] * $itemData['precio_unitario']) - ($itemData['descuento'] ?? 0);
                $subtotal += $itemSubtotal;

                CotizacionItem::create([
                    'cotizacion_id' => $cotizacion->id,
                    'producto_id' => $itemData['producto_id'],
                    'cantidad' => $itemData['cantidad'],
                    'precio_unitario' => $itemData['precio_unitario'],
                    'descuento' => $itemData['descuento'] ?? 0,
                    'descripcion' => $itemData['descripcion'] ?? null,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            // Calcular totales
            $impuestoMonto = $subtotal * ($cotizacion->impuesto / 100);
            $total = $subtotal + $impuestoMonto - $cotizacion->descuento;

            $cotizacion->update([
                'subtotal' => $subtotal,
                'total' => $total
            ]);

            DB::commit();

            return response()->json([
                'mensaje' => 'Cotización creada correctamente',
                'data' => $cotizacion->load(['customer', 'user', 'items.producto'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al crear la cotización: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una cotización específica
     */
    public function show($id): JsonResponse
    {
        try {
            // Usar enfoque directo sin route model binding
            $cotizacion = Cotizacion::withoutTrashed()
                ->with(['customer', 'user', 'items.producto'])
                ->find($id);

            if (!$cotizacion) {
                return response()->json([
                    'error' => 'Cotización no encontrada'
                ], 404);
            }

            return response()->json([
                'mensaje' => 'Cotización obtenida correctamente',
                'data' => $cotizacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la cotización: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una cotización
     */
    public function update(Request $request, Cotizacion $cotizacion): JsonResponse
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'nullable|exists:customers,id',
                'estado' => 'nullable|in:generada,entregada,vendida,rechazada',
                'impuesto' => 'nullable|numeric|min:0',
                'descuento' => 'nullable|numeric|min:0',
                'notas' => 'nullable|string',
                'terminos' => 'nullable|string',
                'items' => 'nullable|array',
                'items.*.id' => 'nullable|exists:cotizacion_items,id',
                'items.*.producto_id' => 'required|exists:productos,id',
                'items.*.cantidad' => 'required|integer|min:1',
                'items.*.precio_unitario' => 'required|numeric|min:0',
                'items.*.descuento' => 'nullable|numeric|min:0',
                'items.*.descripcion' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Actualizar datos básicos de la cotización
            $cotizacion->update([
                'customer_id' => $validated['customer_id'] ?? $cotizacion->customer_id,
                'estado' => $validated['estado'] ?? $cotizacion->estado,
                'impuesto' => $validated['impuesto'] ?? $cotizacion->impuesto,
                'descuento' => $validated['descuento'] ?? $cotizacion->descuento,
                'notas' => $validated['notas'] ?? $cotizacion->notas,
                'terminos' => $validated['terminos'] ?? $cotizacion->terminos,
            ]);

            // Actualizar items si se proporcionan
            if (isset($validated['items'])) {
                // Eliminar items existentes
                $cotizacion->items()->delete();

                // Crear nuevos items
                $subtotal = 0;
                foreach ($validated['items'] as $itemData) {
                    $itemSubtotal = ($itemData['cantidad'] * $itemData['precio_unitario']) - ($itemData['descuento'] ?? 0);
                    $subtotal += $itemSubtotal;

                    CotizacionItem::create([
                        'cotizacion_id' => $cotizacion->id,
                        'producto_id' => $itemData['producto_id'],
                        'cantidad' => $itemData['cantidad'],
                        'precio_unitario' => $itemData['precio_unitario'],
                        'descuento' => $itemData['descuento'] ?? 0,
                        'descripcion' => $itemData['descripcion'] ?? null,
                        'subtotal' => $itemSubtotal,
                    ]);
                }

                // Recalcular totales
                $impuestoMonto = $subtotal * ($cotizacion->impuesto / 100);
                $total = $subtotal + $impuestoMonto - $cotizacion->descuento;

                $cotizacion->update([
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);
            }

            DB::commit();

            return response()->json([
                'mensaje' => 'Cotización actualizada correctamente',
                'data' => $cotizacion->load(['customer', 'user', 'items.producto'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar la cotización: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una cotización
     */
    public function destroy(Cotizacion $cotizacion): JsonResponse
    {
        try {
            $cotizacion->delete();

            return response()->json([
                'mensaje' => 'Cotización eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la cotización: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar múltiples cotizaciones
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:cotizaciones,id'
            ]);

            $cantidad = count($validated['ids']);
            
            // Eliminar las cotizaciones (soft delete)
            Cotizacion::whereIn('id', $validated['ids'])->delete();

            return response()->json([
                'mensaje' => "$cantidad cotización" . ($cantidad > 1 ? 'es eliminadas' : ' eliminada') . " correctamente",
                'cantidad' => $cantidad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar las cotizaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar estado de una cotización
     */
    public function actualizarEstado(Request $request, Cotizacion $cotizacion): JsonResponse
    {
        try {
            $validated = $request->validate([
                'estado' => 'required|in:generada,entregada,vendida,rechazada'
            ]);

            $cotizacion->update(['estado' => $validated['estado']]);

            return response()->json([
                'mensaje' => 'Estado actualizado correctamente',
                'data' => $cotizacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de cotizaciones
     */
    public function estadisticas(): JsonResponse
    {
        try {
            $stats = [
                'total' => Cotizacion::withoutTrashed()->count(),
                'generada' => Cotizacion::withoutTrashed()->where('estado', 'generada')->count(),
                'entregada' => Cotizacion::withoutTrashed()->where('estado', 'entregada')->count(),
                'vendida' => Cotizacion::withoutTrashed()->where('estado', 'vendida')->count(),
                'rechazada' => Cotizacion::withoutTrashed()->where('estado', 'rechazada')->count(),
                'monto_total_vendidas' => Cotizacion::withoutTrashed()->where('estado', 'vendida')->sum('total'),
            ];

            return response()->json([
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }
}
