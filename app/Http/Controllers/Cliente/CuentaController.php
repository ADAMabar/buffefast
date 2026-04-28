<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\Sesion;
use Illuminate\Support\Facades\Log;

class CuentaController extends Controller
{
    /**
     * Muestra el resumen de pedidos y la cuenta de la mesa.
     */
    public function index()
    {
        try {
            // Verificamos que haya una sesión en la memoria del móvil antes de buscar en BD
            $sesion_id = session('sesion_id');
            if (!$sesion_id) {
                return redirect()->route('cliente.inicio')->with('error', 'Tu sesión ha caducado.');
            }

            // Traemos la sesión y su mesa en UNA SOLA consulta (Eager Loading)
            $sesion = Sesion::with('mesa')->find($sesion_id);

            //  EL GUARDIA: Si no existe en BD o el admin la cerró, lo expulsamos
            if (!$sesion || $sesion->estado === 'cerrada') {
                session()->forget(['sesion_id', 'cliente_id', 'carrito', 'carrito_count']);
                return redirect()->route('cliente.inicio')->with('error', 'Tu mesa ha sido cerrada y cobrada. ¡Gracias por tu visita!');
            }

            //  Traemos el historial de pedidos de esta sesión
            $pedidos = Pedido::with('platos')
                ->where('sesion_id', $sesion_id)
                ->orderBy('ronda', 'desc')
                ->get();
            // Simplemente contamos cuántos elementos tiene la colección que ya trajimos.
            $rondaActual = $pedidos->count() + 1;

            return view('cliente.cuenta', compact('sesion', 'pedidos', 'rondaActual'));
            

        } catch (\Exception $e) {
            Log::error('Error al cargar la cuenta del cliente: ' . $e->getMessage());
            
            return redirect()->route('cliente.carta')->with('error', 'Hubo un problema al cargar tu cuenta. Por favor, avisa a un camarero.');
        }
    }

    public function indexSobreNosotros(){
        //verifico otra vez si la sesion está activa
        $sesion_id = session('sesion_id');
        
         $pedidos = Pedido::with('platos')
                ->where('sesion_id', $sesion_id)
                ->orderBy('ronda', 'desc')
                ->get();
                
        $rondaActual = $pedidos->count() + 1;

        $sesion = Sesion::with('mesa')->find($sesion_id);

        if (!$sesion || $sesion->estado === 'cerrada') {
            session()->forget(['sesion_id', 'cliente_id', 'carrito', 'carrito_count']);
            return redirect()->route('cliente.inicio')->with('error', 'Tu mesa ha sido cerrada.');
        }

       if (!$sesion_id) {
            return redirect()->route('cliente.inicio')->with('error', 'Tu sesión ha caducado.');
        }

        return view('cliente.sobreNosotros', compact('sesion', 'rondaActual'));

    }

public function pedirCuenta(Request $request)
{
    $sesion_id = session('sesion_id');
    
    if (!$sesion_id) {
        return redirect()->route('cliente.inicio')->with('error', 'Sesión no válida.');
    }
 
    $sesion = Sesion::find($sesion_id);
 
    if (!$sesion || $sesion->estado === 'cerrada') {
        return redirect()->route('cliente.inicio')->with('error', 'La mesa ya está cerrada.');
    }
 
    // Cambiar estado a "solicitando_cuenta"
    $sesion->update(['estado' => 'solicitando_cuenta']);
 
    return back()->with('success', 'Has solicitado la cuenta. Un camarero vendrá enseguida.');
}
}