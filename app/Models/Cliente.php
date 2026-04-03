<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'sesion_id'
    ];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }


}