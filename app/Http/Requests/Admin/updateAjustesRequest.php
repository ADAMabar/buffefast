<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAjustesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tiempo_ronda_minutos' => ['required', 'integer', 'min:0', 'max:120'],
            'limite_platos_ronda' => ['required', 'integer', 'min:1', 'max:50'],
            'precio_penalizacion' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'nombre_restaurante' => ['required', 'string', 'min:2', 'max:100'],
            'porcentaje_impuestos' => ['required', 'numeric', 'min:0', 'max:100'],
            // Los checkbox (booleanos) se validan diferente porque si no se marcan, no se envían
            'penalizacion_activa' => ['nullable'],
            'aceptacion_automatica' => ['nullable'],
            'sonido_cocina' => ['nullable'],
            'nombre_restaurante' => ['required', 'string', 'min:2', 'max:100'],
            'logo_restaurante' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}