<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->user ? $this->user->id : auth()->id();
        
        return [
            'username' => 'sometimes|required|string|max:50|unique:users,username,' . $userId,
            'name' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $userId,
            'telegram_id' => 'sometimes|required|integer|exists:telegrams,id',
            'phone_id' => 'sometimes|required|integer|exists:phones,id',
        ];
    }
}
