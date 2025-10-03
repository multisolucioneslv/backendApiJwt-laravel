<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:50|unique:users',
            'name' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'telegram_id' => 'sometimes|required|integer|exists:telegrams,id',
            'phone_id' => 'sometimes|required|integer|exists:phones,id',
        ];
    }
    public function messages(): array
    {
        return [
            'username.required' => 'El nombre usuario es requerido',
            'username.unique' => 'El usuario ya existe',
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede tener más de 50 caracteres',
            'lastname.required' => 'El apellido es requerido',
            'lastname.max' => 'El apellido no puede tener más de 50 caracteres',
            'email.required' => 'El coreo es requerido',
            'email.email' => 'El correo no es válido',
            'email.unique' => 'El correo ya existe',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'telegram_id.required' => 'El chat id es requerido',
            'telegram_id.exists' => 'El chat id seleccionado no es válido',
            'te.max' => 'El chat id no puede tener más de 50 caracteres',
            'phone_id.required' => 'El teléfono es requerido',
            'phone_id.exists' => 'El teléfono seleccionado no es válido',
        ];
    }
}
