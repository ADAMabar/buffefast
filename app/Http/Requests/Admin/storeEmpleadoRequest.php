<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'rol' => ['required', 'string', 'in:admin,camarero,cocinero'],
            'email' => ['exclude_if:rol,camarero', 'required', 'email', 'unique:usuarios,email'],
            'password' => ['exclude_if:rol,camarero', 'required', 'string', 'min:8', 'confirmed'],

        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Ese correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}