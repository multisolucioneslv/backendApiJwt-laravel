<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

            // Filtros opcionales
            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->has('desde_fecha')) {
                $query->whereDate('fecha_cotizacion', '>=', $request->desde_fecha);
            }

            if ($request->has('hasta_fecha')) {
                $query->whereDate('fecha_cotizacion', '<=', $request->hasta_fecha);
            }

            // Ordenamiento
            $ordenarPor = $request->get('ordenar_por', 'created_at');
            $ordenDireccion = $request->get('orden_direccion', 'desc');

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
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'fecha_cotizacion' => 'required|date',
                'fecha_vencimiento' => 'required|date|after:fecha_cotizacion',
                'estado' => 'nullable|in:borrador,enviada,aprobada,rechazada,vencida',
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

            DB::beginTransaction();

            // Crear la cotización
            $cotizacion = Cotizacion::create([
                'numero_cotizacion' => Cotizacion::generarNumeroCotizacion(),
                'customer_id' => $validated['customer_id'],
                'user_id' => Auth::id(),
                'fecha_cotizacion' => $validated['fecha_cotizacion'],
                'fecha_vencimiento' => $validated['fecha_vencimiento'],
                'estado' => $validated['estado'] ?? 'borrador',
                'impuesto' => $validated['impuesto'] ?? 0,
                'descuento' => $validated['descuento'] ?? 0,
                'notas' => $validated['notas'] ?? null,
                'terminos' => $validated['terminos'] ?? null,
            ]);

            // Crear los items
            foreach ($validated['items'] as $item) {
                CotizacionItem::create([
                    'cotizacion_id' => $cotizacion->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descuento' => $item['descuento'] ?? 0,
                    'descripcion' => $item['descripcion'] ?? null,
                ]);
            }

            // Calcular totales
            $cotizacion->calcularTotal();

            DB::commit();

            return response()->json([
                'mensaje' => 'Cotización creada correctamente',
                'data' => $cotizacion->load(['customer', 'user', 'items.producto'])
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Datos de validación incorrectos',
                'detalles' => $e->errors()
            ], 422);
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
    public function show(Cotizacion $cotizacion): JsonResponse
    {
        try {
            return response()->json([
                'mensaje' => 'Cotización obtenida correctamente',
                'data' => $cotizacion->load(['customer', 'user', 'items.producto'])
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
                'fecha_cotizacion' => 'nullable|date',
                'fecha_vencimiento' => 'nullable|date',
                'estado' => 'nullable|in:borrador,enviada,aprobada,rechazada,vencida',
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
                'fecha_cotizacion' => $validated['fecha_cotizacion'] ?? $cotizacion->fecha_cotizacion,
                'fecha_vencimiento' => $validated['fecha_vencimiento'] ?? $cotizacion->fecha_vencimiento,
                'estado' => $validated['estado'] ?? $cotizacion->estado,
                'impuesto' => $validated['impuesto'] ?? $cotizacion->impuesto,
                'descuento' => $validated['descuento'] ?? $cotizacion->descuento,
                'notas' => $validated['notas'] ?? $cotizacion->notas,
                'terminos' => $validated['terminos'] ?? $cotizacion->terminos,
            ]);

            // Actualizar items si se proporcionan
            if (isset($validated['items'])) {
                // Eliminar items anteriores
                $cotizacion->items()->delete();

                // Crear nuevos items
                foreach ($validated['items'] as $item) {
                    CotizacionItem::create([
                        'cotizacion_id' => $cotizacion->id,
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'descuento' => $item['descuento'] ?? 0,
                        'descripcion' => $item['descripcion'] ?? null,
                    ]);
                }

                // Recalcular totales
                $cotizacion->calcularTotal();
            }

            DB::commit();

            return response()->json([
                'mensaje' => 'Cotización actualizada correctamente',
                'data' => $cotizacion->load(['customer', 'user', 'items.producto'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Datos de validación incorrectos',
                'detalles' => $e->errors()
            ], 422);
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
     * Cambiar el estado de una cotización
     */
    public function actualizarEstado(Request $request, Cotizacion $cotizacion): JsonResponse
    {
        try {
            $validated = $request->validate([
                'estado' => 'required|in:borrador,enviada,aprobada,rechazada,vencida'
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
                'total' => Cotizacion::count(),
                'borrador' => Cotizacion::where('estado', 'borrador')->count(),
                'enviada' => Cotizacion::where('estado', 'enviada')->count(),
                'aprobada' => Cotizacion::where('estado', 'aprobada')->count(),
                'rechazada' => Cotizacion::where('estado', 'rechazada')->count(),
                'vencida' => Cotizacion::where('estado', 'vencida')->count(),
                'monto_total_aprobadas' => Cotizacion::where('estado', 'aprobada')->sum('total'),
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
