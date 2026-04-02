<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plato;
use App\Models\Categorias;
use App\Models\Sesion;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si hay sesión activa
        if (!session()->has('sesion_id')) {
            return redirect()->route('cliente.inicio')->withErrors(['codigo' => 'Debes ingresar el código de mesa para acceder.']);
        }

        // 1. Traemos todas las categorías ordenadas por su campo 'orden'
        $categorias = Categorias::orderBy('orden')->get();

        // 2. Traemos los platos ACTIVOS (usando el scope que creamos en el modelo)
        $query = Plato::activos();

        // Si el cliente ha pulsado en el botón de una categoría, filtramos:
        if ($request->has('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }
        $platos = $query->get();

        // 3. Recuperamos la sesión actual (asumiendo que guardaste el sesion_id al hacer login)
        $sesion = Sesion::with('mesa')->find(session('sesion_id'));

        // 4. Calculamos en qué ronda estamos (si no hay sesión, es la ronda 1)
        $rondaActual = 1;
        if ($sesion) {
            $rondaActual = $sesion->pedidos()->count() + 1;
        }

        // 5. Le mandamos todos estos datos a tu vista Blade
        return view('cliente.carta', compact('categorias', 'platos', 'sesion', 'rondaActual'));
    }
}