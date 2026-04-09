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

            // Si es admin/cocinero: Exigimos email único y contraseña
            'email' => ['exclude_if:rol,camarero', 'required', 'email', 'unique:usuarios,email'],
            'password' => ['exclude_if:rol,camarero', 'required', 'string', 'min:8', 'confirmed'],

            // Si es camarero: Exigimos PIN de 4 dígitos único
            'pin' => ['exclude_unless:rol,camarero', 'required', 'numeric', 'digits:4', 'unique:usuarios,pin'],
        ];
    }

    public function messages(): array
    {
        return [
            'pin.unique' => 'Ese PIN ya lo usa otro camarero.',
            'email.unique' => 'Ese correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}