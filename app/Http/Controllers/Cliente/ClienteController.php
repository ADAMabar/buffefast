<?php
namespace App\Http\Controllers\Cliente;

use App\Models\Plato;
use App\Models\Sesion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categorias;

class ClienteController extends Controller
{
    public function carta()
    {
        // Verificar si hay sesión activa
        if (!session()->has('sesion_id')) {
            return redirect()->route('cliente.login')->withErrors(['codigo' => 'Tu sesión ha expirado.']);
        }

        $sesion = Sesion::with('mesa')->find(session('sesion_id'));

        // Si por alguna razón la sesión ya no existe en BD
        if (!$sesion) {
            session()->forget('sesion_id');
            return redirect()->route('cliente.login');
        }

        $categorias = Categorias::orderBy('orden', 'asc')->get();
        $platos = Plato::where('activo', 1)->get();

        // Simulamos la ronda actual (esto luego vendrá de la lógica de pedidos)
        $rondaActual = 1;

        return view('cliente.carta', compact('sesion', 'categorias', 'platos', 'rondaActual'));
    }
}