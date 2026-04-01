<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoPlato extends Model
{
    protected $table = 'pedido_platos';
    protected $primaryKey = 'id';
    protected $fillable = ['pedido_id', 'plato_id', 'cantidad'];
    public $timestamps = false;

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }
}