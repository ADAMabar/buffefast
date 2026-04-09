<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExportarDatosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_inicio' => ['required', 'date', 'before_or_equal:today'],
            'fecha_fin' => ['required', 'date', 'before_or_equal:today', 'after_or_equal:fecha_inicio'],
        ];
    }
}
