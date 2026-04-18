<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Sesion;
use App\Models\Plato;

class Pedido extends Model
{
    protected $table = 'pedidos';

    // CORRECCIÓN: Quitamos plato_id y cantidad de aquí, añadimos cliente_id y ronda
    protected $fillable = ['sesion_id', 'cliente_id', 'ronda', 'estado'];

    // Un pedido pertenece a una sesión
    public function sesion()
    {
        return $this->belongsTo(Sesion::class);
    }

    // Un pedido pertenece a un cliente específico
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación Muchos a Muchos con Platos
    // app/Models/Pedido.php
    public function platos()
    {
        return $this->belongsToMany(Plato::class, 'pedido_platos')
            ->withPivot('cantidad');
    }
    public function detalles()
    {
        return $this->hasMany(PedidoPlato::class);
    }
    // app/Models/Pedido.php


}