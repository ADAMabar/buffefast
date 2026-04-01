<?php
namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Categorias;
use App\Models\Plato;
use App\Models\Sesion;
use Illuminate\Http\Request;

function index(Request $request)
{
    // Recuperamos la sesión actual usando el ID que guardamos en el login
    $sesion = Sesion::with('mesa')->findOrFail(session('sesion_id'));

    // Obtenemos todas las categorías ordenadas
    $categorias = Categorias::orderBy('orden')->get();

    // Obtenemos los platos (podemos filtrar si el usuario pulsa en una categoría)
    $query = Plato::activos();
    if ($request->has('categoria')) {
        $query->where('categoria_id', $request->categoria);
    }
    $platos = $query->get();

    // Simulamos la ronda (esto vendría de contar cuántos pedidos tiene la sesión)
    $rondaActual = $sesion->pedidos()->count() + 1;

    return view('cliente.carta', compact('sesion', 'categorias', 'platos', 'rondaActual'));
}