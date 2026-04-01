<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $table = 'platos';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre', 'descripcion', 'categoria_id', 'imagen', 'activo'];
    public $timestamps = true;

    public function categoria()
    {
        return $this->belongsTo(Categorias::class);
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_platos')->withPivot('cantidad');
    }

    public function pedidoPlatos()
    {
        return $this->hasMany(PedidoPlato::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }


}