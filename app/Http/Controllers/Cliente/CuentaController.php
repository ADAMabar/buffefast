<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\Sesion;

class CuentaController extends Controller
{
    public function index()
    {
        $sesion_id = session('sesion_id');

        if (!$sesion_id) {
            return redirect('cliente')->route('cliente.inicio')->with('error', 'Tu sesión ha caducado');
        }

        $sesion = Sesion::with('mesa')->find($sesion_id);

        $pedidos = Pedido::with('platos')
            ->where('sesion_id', $sesion_id)
            ->orderBy('ronda', 'desc')
            ->get();

        return view('cliente.cuenta', compact('sesion', 'pedidos'));


    }


}
