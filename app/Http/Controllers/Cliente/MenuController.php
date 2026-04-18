<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plato;
use App\Models\Categorias;
use App\Models\Sesion;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    /**
     * Muestra la carta digital, permitiendo filtrar por categorías.
     */
    public function index(Request $request)
    {
        try {
            // 1. EL GUARDIA DE SEGURIDAD (Orden optimizado)
            // Primero verificamos si hay sesión en la memoria del navegador
            if (!session()->has('sesion_id')) {
                return redirect()->route('cliente.inicio')->withErrors(['codigo' => 'Debes ingresar el código de mesa para acceder.']);
            }

            $sesion_id = session('sesion_id');
            // Buscamos la sesión y su mesa en una sola consulta (Eager Loading)
            $sesion = Sesion::with('mesa')->find($sesion_id);

            // Si no hay sesión en la BD, o el administrador la ha marcado como 'cerrada'
            if (!$sesion || $sesion->estado === 'cerrada') {
                // Vaciamos la memoria (cookies) al móvil del cliente por completo
                session()->forget(['sesion_id', 'cliente_id', 'carrito', 'carrito_count']);

                // Lo expulsamos a la pantalla de inicio de sesión
                return redirect()->route('cliente.inicio')->with('error', 'Tu mesa ha sido cerrada y cobrada. ¡Gracias por tu visita!');
            }

            // 2. Traemos todas las categorías para pintar las pestañas/botones del menú
            $categorias = Categorias::orderBy('orden')->get();

            // 3. Traemos los platos ACTIVOS (usando el scope de tu modelo)
            $query = Plato::activos();

            // Si el cliente ha pulsado en el botón de una categoría, filtramos la consulta
            if ($request->has('categoria')) {
                $query->where('categoria_id', $request->categoria);
            }
            $platos = $query->get();

            // 4. Calculamos en qué ronda estamos de forma dinámica basada en la BD
            $rondaActual = $sesion->pedidos()->count() + 1;

            // 5. Le mandamos todos estos datos a tu vista Blade
            return view('cliente.carta', compact('categorias', 'platos', 'sesion', 'rondaActual'));

        } catch (\Exception $e) {
            Log::error('Error al cargar la carta del cliente: ' . $e->getMessage());
            return redirect()->route('cliente.inicio')->with('error', 'Ocurrió un error al cargar el menú. Por favor, avisa a un camarero.');
        }
    }
}