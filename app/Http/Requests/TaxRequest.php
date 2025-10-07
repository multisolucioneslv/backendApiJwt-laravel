<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ];

        // Si es un update (PUT/PATCH), hacer los campos opcionales
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'rate' => 'sometimes|required|numeric|min:0|max:100',
                'is_active' => 'sometimes|boolean',
                'description' => 'nullable|string'
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del impuesto es obligatorio',
            'name.max' => 'El nombre no puede exceder 255 caracteres',
            'rate.required' => 'La tasa del impuesto es obligatoria',
            'rate.numeric' => 'La tasa debe ser un número',
            'rate.min' => 'La tasa no puede ser negativa',
            'rate.max' => 'La tasa no puede exceder el 100%',
            'is_active.boolean' => 'El estado debe ser verdadero o falso'
        ];
    }
}
