<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends ApiFormRequest
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'El nombre del libro es obligatorio.',
            'name.string'    => 'El nombre debe ser una cadena de texto.',
            'name.max'       => 'El nombre no puede superar los 255 caracteres.',

            'email.required' => 'El correo es obligatorio.',
            'email.string'   => 'El correo debe ser una cadena de texto.',
            'email.email'    => 'El correo debe ser un correo válido.',
            'email.max'      => 'El correo no puede superar los 255 caracteres.',
            'email.unique'   => 'El correo ya está en uso',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string'   => 'La contraseña debe ser una cadena de texto.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }
}
