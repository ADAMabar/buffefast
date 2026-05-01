<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Sesion extends Model
{
    protected $table = 'sesiones';

  
    protected $fillable = ['mesa_id', 'codigo', 'estado', 'total_cobrado'];

    public $timestamps = true;

   
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

  
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
    
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}