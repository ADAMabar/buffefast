<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class AccesoClienteRequest extends FormRequest
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
            'codigo' => ['required', 'string', 'max:6'],
            'nombre' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'Por favor, introduce el código de tu mesa.',
            'nombre.required' => 'Por favor, dinos tu nombre.',
        ];
    }
}
