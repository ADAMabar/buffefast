<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $table = 'platos';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre', 'descripcion', 'categoria_id', 'imagen', 'activo'];
    public $timestamps = true;
    protected $casts = [
        'activo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'categoria_id');
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