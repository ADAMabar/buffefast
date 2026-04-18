<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    // 1. Los campos que podemos rellenar masivamente
    protected $fillable = [
        'nombre',
        'activa',
    ];

    // 2. Magia de Laravel: Le decimos que 'activa' siempre es un booleano
    protected $casts = [
        'activa' => 'boolean',
    ];

    // 3. Relación de futuro: Una caja "tiene muchas" ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}