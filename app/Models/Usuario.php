<?php
namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    // Le indicamos el nombre exacto de tu tabla
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol'
    ];

    protected $hidden = [
        'password',
    ];
}