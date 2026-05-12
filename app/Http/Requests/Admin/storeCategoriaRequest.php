<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class storeCategoriaRequest extends FormRequest
{
  protected $errorBag = 'categoriaBag';

    public function authorize(): bool
    {
        return true;
    }

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

        'nombre.required' => 'Por favor, introduce el nombre de la categoria.',
        'nombre.unique' => '¡Esa categoria ya existe!',
        'nombre.regex' => 'El nombre de la categoria solo puede contener letras y espacios.',
        'orden.required' => 'Por favor, introduce el orden de la categoria.',
        'orden.min' => 'El orden debe ser 0 o mayor.'
    ];
}
}
