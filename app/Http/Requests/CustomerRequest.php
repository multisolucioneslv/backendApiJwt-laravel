<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $customerId = $this->route('customer') ? $this->route('customer')->id : null;
        
        $rules = [
            'name' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'phone_id' => 'sometimes|integer|exists:phones,id',
            'telegram_id' => 'sometimes|integer|exists:telegrams,id',
            'sex_id' => 'required|integer|exists:sexs,id',
            'address' => 'nullable|string|max:255',
            'user_id' => 'sometimes|integer|exists:users,id'
        ];

        // Para actualización, hacer email único excepto para el registro actual
        if ($customerId) {
            $rules['email'] = 'sometimes|required|email|max:255|unique:customers,email,' . $customerId;
            $rules['name'] = 'sometimes|required|string|max:50';
            $rules['lastname'] = 'sometimes|required|string|max:50';
            $rules['sex_id'] = 'sometimes|required|integer|exists:sexs,id';
        } else {
            $rules['email'] = 'required|email|max:255|unique:customers,email';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del cliente es obligatorio.',
            'name.max' => 'El nombre no puede exceder 50 caracteres.',
            'lastname.required' => 'El apellido del cliente es obligatorio.',
            'lastname.max' => 'El apellido no puede exceder 50 caracteres.',
            'email.required' => 'El email del cliente es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'email.max' => 'El email no puede exceder 255 caracteres.',
            'phone_id.exists' => 'El teléfono seleccionado no existe.',
            'telegram_id.exists' => 'El Telegram ID seleccionado no existe.',
            'sex_id.required' => 'El sexo del cliente es obligatorio.',
            'sex_id.exists' => 'El sexo seleccionado no existe.',
            'address.max' => 'La dirección no puede exceder 255 caracteres.',
            'user_id.exists' => 'El usuario seleccionado no existe.'
        ];
    }
}