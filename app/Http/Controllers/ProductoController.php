<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\ProductoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Parámetros de ordenamiento
            $sortBy = $request->get('sort_by', 'id'); // Campo por defecto
            $sortOrder = $request->get('sort_order', 'asc'); // Orden por defecto
            
            // Validar campos permitidos para ordenamiento
            $allowedSortFields = ['id', 'name', 'codigo', 'price', 'stock', 'created_at', 'updated_at'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'id';
            }
            
            // Validar orden
            $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';
            
            // Parámetro de cantidad por página
            $perPage = $request->get('per_page', 10);
            $allowedPerPage = [10, 25, 50, 100, 'all'];
            
            if (!in_array($perPage, $allowedPerPage)) {
                $perPage = 10;
            }
            
            // Si es 'all', obtener todos los productos sin paginación
            if ($perPage === 'all') {
                $productos = Producto::orderBy($sortBy, $sortOrder)->get();
                return response()->json([
                    'message' => 'Productos obtenidos correctamente',
                    'data' => $productos,
                    'sorting' => [
                        'sort_by' => $sortBy,
                        'sort_order' => $sortOrder
                    ],
                    'pagination' => [
                        'total' => $productos->count(),
                        'per_page' => 'all',
                        'current_page' => 1,
                        'last_page' => 1,
                        'from' => 1,
                        'to' => $productos->count()
                    ]
                ]);
            }
            
            // Paginación normal
            $productos = Producto::orderBy($sortBy, $sortOrder)->paginate($perPage);
            
            return response()->json([
                'message' => 'Productos obtenidos correctamente',
                'data' => $productos,
                'sorting' => [
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ],
                'pagination' => [
                    'total' => $productos->total(),
                    'per_page' => $productos->perPage(),
                    'current_page' => $productos->currentPage(),
                    'last_page' => $productos->lastPage(),
                    'from' => $productos->firstItem(),
                    'to' => $productos->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los productos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            
            // Si hay imágenes, asegurar que se guarden correctamente
            if (isset($validatedData['images']) && is_array($validatedData['images'])) {
                // Filtrar imágenes vacías
                $validatedData['images'] = array_filter($validatedData['images'], function($image) {
                    return !empty($image);
                });
                
                // Si no hay imágenes válidas, establecer como null
                if (empty($validatedData['images'])) {
                    $validatedData['images'] = null;
                }
            }
            
            $producto = Producto::create($validatedData);

            return response()->json([
                'message' => 'Producto creado correctamente',
                'data' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Producto obtenido correctamente',
                'data' => $producto
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoRequest $request, Producto $producto): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            
            \Log::info('Datos recibidos para actualizar producto:', [
                'producto_id' => $producto->id,
                'validated_data' => $validatedData,
                'images_count' => isset($validatedData['images']) ? count($validatedData['images']) : 0
            ]);
            
            // Si hay imágenes nuevas, asegurar que se guarden correctamente
            if (isset($validatedData['images']) && is_array($validatedData['images'])) {
                // Filtrar imágenes vacías
                $validatedData['images'] = array_filter($validatedData['images'], function($image) {
                    return !empty($image);
                });
                
                // Si no hay imágenes válidas, establecer como null
                if (empty($validatedData['images'])) {
                    $validatedData['images'] = null;
                }
                
                \Log::info('Imágenes procesadas:', [
                    'images_after_filter' => $validatedData['images']
                ]);
            }
            
            $producto->update($validatedData);
            
            \Log::info('Producto actualizado:', [
                'producto_id' => $producto->id,
                'images_saved' => $producto->images
            ]);

            return response()->json([
                'message' => 'Producto actualizado correctamente',
                'data' => $producto->fresh() // Obtener el producto actualizado desde la BD
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar producto:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al actualizar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto): JsonResponse
    {
        try {
            $producto->delete();

            return response()->json([
                'message' => 'Producto eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }
}
