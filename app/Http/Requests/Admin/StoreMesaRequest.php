<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMesaRequest extends FormRequest
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
            'numero' => 'required|integer|min:1|unique:mesas,numero',
            'capacidad' => 'required|integer|min:1|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'numero.required' => 'El número de la mesa es obligatorio.',
            'numero.integer' => 'El número de la mesa debe ser un número entero.',
            'numero.min' => 'El número de la mesa debe ser mayor o igual a 1.',
            'numero.unique' => 'La mesa ya existe.',
            'capacidad.required' => 'La capacidad de la mesa es obligatoria.',
            'capacidad.integer' => 'La capacidad de la mesa debe ser un número entero.',
            'capacidad.min' => 'La capacidad de la mesa debe ser mayor o igual a 1.',
            'capacidad.max' => 'La capacidad de la mesa debe ser menor o igual a 20.',
        ];
    }
}
