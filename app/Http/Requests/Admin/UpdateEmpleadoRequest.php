<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Cogemos el ID de la URL (ejemplo: /admin/empleado/5/editar -> cogemos el 5)
        $empleadoId = $this->route('id');

        return [
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'rol' => ['required', 'string', 'in:admin,camarero,cocinero'],

            // Ignora el email del usuario que estamos editando
            'email' => ['exclude_if:rol,camarero', 'required', 'email', Rule::unique('usuarios', 'email')->ignore($empleadoId)],
            'password' => ['exclude_if:rol,camarero', 'nullable', 'string', 'min:8', 'confirmed'],

            // Ignora el PIN del camarero que estamos editando
            'pin' => ['exclude_unless:rol,camarero', 'required', 'numeric', 'digits:4', Rule::unique('usuarios', 'pin')->ignore($empleadoId)],
        ];
    }
}