<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// (No hace falta importar Mesa, Pedido o Cliente si están en la misma carpeta App\Models)

class Sesion extends Model
{
    protected $table = 'sesiones';

    // CORRECCIÓN 1: Quitamos cliente_id y añadimos 'codigo'
    protected $fillable = ['mesa_id', 'codigo', 'estado', 'total_cobrado'];

    public $timestamps = true;

    // 1. Una sesión pertenece a una mesa física
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    // 2. CORRECCIÓN 2: Una sesión TIENE MUCHOS clientes (no belongsTo)
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    // 3. Una sesión tiene muchos pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}