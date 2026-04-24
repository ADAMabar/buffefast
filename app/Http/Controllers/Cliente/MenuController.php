<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plato;
use App\Models\Categorias;
use App\Models\Sesion;
use Illuminate\Support\Facades\Log;
use App\Models\Pedido;


class MenuController extends Controller
{
    /**
     * Muestra la carta digital, permitiendo filtrar por categorías.
     */
public function index(Request $request)
    {
        try {
            // 1. EL GUARDIA DE SEGURIDAD
            if (!session()->has('sesion_id')) {
                return redirect()->route('cliente.inicio')->withErrors(['codigo' => 'Debes ingresar el código de mesa para acceder.']);
            }

            // Sacamos los datos de la sesión del móvil
            $sesion_id = session('sesion_id');
            $cliente_id = session('cliente_id'); // <-- ¡Clave para saber de quién son los platos!

            // Buscamos la sesión y su mesa
            $sesion = Sesion::with('mesa')->find($sesion_id);

            if (!$sesion || $sesion->estado === 'cerrada') {
                session()->forget(['sesion_id', 'cliente_id', 'carrito', 'carrito_count']);
                return redirect()->route('cliente.inicio')->with('error', 'Tu mesa ha sido cerrada y cobrada. ¡Gracias por tu visita!');
            }

            // 2. Traemos categorías y platos
            $categorias = Categorias::orderBy('orden')->get(); // OJO: Tu modelo se suele llamar Categoria en singular
            $query = Plato::activos();

            if ($request->has('categoria')) {
                $query->where('categoria_id', $request->categoria);
            }
            $platos = $query->get();

            
            $rondaActual = $sesion->pedidos()->count() + 1;
            $nombreRestaurante = configuracion('nombre_restaurante');
            

            $carritoActual = session('carrito', []);

          
            return view('cliente.carta', compact('categorias', 'platos', 'sesion', 'rondaActual', 'nombreRestaurante', 'carritoActual'));

        } catch (\Exception $e) {
            Log::error('Error al cargar la carta del cliente: ' . $e->getMessage());
            return redirect()->route('cliente.inicio')->with('error', 'Ocurrió un error al cargar el menú. Por favor, avisa a un camarero.');
        }
    }
}