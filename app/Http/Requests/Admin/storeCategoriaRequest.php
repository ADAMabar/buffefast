<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class storeCategoriaRequest extends FormRequest
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
        'nombre' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|unique:categorias,nombre',
        'orden' => 'required|integer|min:0'
    ];
}

public function messages(): array
{
    return [
        'nombre.required' => 'Por favor, introduce el nombre de la categoría.',
        'nombre.unique' => '¡Esa categoría ya existe!',
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'orden.required' => 'Por favor, introduce el orden de la categoría.',
        'orden.min' => 'El orden debe ser 0 o mayor.'
    ];
}
}
