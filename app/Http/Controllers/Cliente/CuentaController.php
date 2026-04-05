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
        // 1. EL GUARDIA DE SEGURIDAD
        $sesion_id = session('sesion_id');
        $sesionDB = Sesion::find($sesion_id);

        // Si no hay sesión en la BD, o el administrador la ha marcado como 'cerrada'
        if (!$sesionDB || $sesionDB->estado === 'cerrada') {
            // Le vaciamos la memoria (cookies) al móvil del cliente por completo
            session()->forget(['sesion_id', 'cliente_id', 'carrito', 'carrito_count']);

            // Lo expulsamos a la pantalla de inicio de sesión
            return redirect()->route('cliente.inicio')->with('error', 'Tu mesa ha sido cerrada y cobrada. ¡Gracias por tu visita!');
        }

        $sesion_id = session('sesion_id');

        if (!$sesion_id) {
            return redirect('cliente')->route('cliente.inicio')->with('error', 'Tu sesión ha caducado');
        }

        $sesion = Sesion::with('mesa')->find($sesion_id);

        $pedidos = Pedido::with('platos')
            ->where('sesion_id', $sesion_id)
            ->orderBy('ronda', 'desc')
            ->get();


        $rondaActual = Pedido::where('sesion_id', $sesion_id)->count() + 1;
        return view('cliente.cuenta', compact('sesion', 'pedidos', 'rondaActual'));


    }


}
