<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telegram_id' => $request->telegram_id,
            'phone_id' => $request->phone_id,
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Error al crear el token'
            ], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        if($request->username){
            $credentials = $request->only('username', 'password');
        }else{
            $credentials = $request->only('email', 'password');
        }
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Las credenciales son incorrectas'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Error al crear el token'
            ], 500);
        }

        return response()->json([
            'token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Error al cerrar sesión, por favor intente nuevamente'
            ], 500);
        }

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    public function getUser(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Cargar los roles del usuario
            $user->load('roles');

            // Preparar la respuesta con los datos del usuario y sus roles
            $userData = [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone_id' => $user->phone_id,
                'status' => $user->status,
                'roles' => $user->roles->pluck('name')->toArray(), // Array de nombres de roles
            ];

            return response()->json($userData);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Error al obtener el perfil del usuario'], 500);
        }
    }

    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Preparar los datos de actualización de forma más segura
            $updateData = [];
            
            // Solo actualizar campos que estén presentes en la request
            $allowedFields = ['username', 'name', 'lastname', 'email', 'telegram_id', 'phone_id'];
            
            foreach ($allowedFields as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->input($field);
                }
            }
            
            // Verificar que hay datos para actualizar
            if (!empty($updateData)) {
                try {
                    // Usar DB facade directamente para evitar problemas con timestamps
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update($updateData);
                } catch (\Exception $eloquentError) {
                    // Si falla DB facade, intentar con Eloquent sin timestamps
                    $user->timestamps = false;
                    $user->where('id', $user->id)->update($updateData);
                    $user->timestamps = true;
                }
            }
            
            // Obtener el usuario actualizado desde la base de datos
            $updatedUser = User::find($user->id);
            
            return response()->json([
                'message' => 'Usuario actualizado correctamente',
                'user' => $updatedUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}