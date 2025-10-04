<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::with(['phone', 'sex']);
            
            // Búsqueda
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            $users = $query->paginate(10);
            return response()->json([
                'message' => 'Usuarios obtenidos correctamente',
                'data' => $users,
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los usuarios: ' . $e->getMessage()
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
                'username' => 'required|string|max:50|unique:users',
                'name' => 'required|string|max:100',
                'lastname' => 'required|string|max:100',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'status' => 'sometimes|string|max:20',
                'phone_id' => 'sometimes|integer|exists:phones,id',
                'telegram_id' => 'sometimes|integer|exists:telegrams,id',
                'sex_id' => 'sometimes|integer|exists:sexs,id'
            ]);

            $data = $request->all();
            $data['password'] = Hash::make($request->password);

            $user = User::create($data);

            return response()->json([
                'message' => 'Usuario creado correctamente',
                'data' => $user->load(['phone', 'sex'])
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Usuario obtenido correctamente',
                'data' => $user->load(['phone', 'sex'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        try {
            $request->validate([
                'username' => 'sometimes|required|string|max:50|unique:users,username,' . $user->id,
                'name' => 'sometimes|required|string|max:100',
                'lastname' => 'sometimes|required|string|max:100',
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:6',
                'status' => 'sometimes|string|max:20',
                'phone_id' => 'sometimes|integer|exists:phones,id',
                'telegram_id' => 'sometimes|integer|exists:telegrams,id',
                'sex_id' => 'sometimes|integer|exists:sexs,id'
            ]);

            $data = $request->except(['password']);

            if ($request->has('password') && !empty($request->password)) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return response()->json([
                'message' => 'Usuario actualizado correctamente',
                'data' => $user->load(['phone', 'sex'])
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json([
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}
