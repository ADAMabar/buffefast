<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Sesion;

class Mesa extends Model
{
    protected $table = 'mesas';

    protected $fillable = ['numero', 'capacidad'];

    public function sesiones()
    {
        return $this->hasMany(Sesion::class);
    }

    // Para obtener todos los clientes que están actualmente en esta mesa
    public function clientes()
    {
        return $this->hasManyThrough(Cliente::class, Sesion::class);
    }
}