<?php


namespace App\Models;
use App\Models\Plato;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'orden'
    ];

    public function platos()
    {
        return $this->hasMany(Plato::class);
    }
}