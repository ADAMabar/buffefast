<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plato;
use App\Models\Sesion;

class CarritoController extends Controller
{
    public function agregar($id)
    {
        // 1. Buscamos el plato en la base de datos para asegurarnos de que existe
        $plato = Plato::findOrFail($id);

        // 2. Traemos el carrito actual de la memoria (si no hay, creamos un array vacío)
        $carrito = session()->get('carrito', []);

        // 3. Lógica del CRUD: Si el plato ya está en el carrito, le sumamos 1 a la cantidad
        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        }
        // Si no está, lo añadimos por primera vez con cantidad 1
        else {
            $carrito[$id] = [
                'nombre' => $plato->nombre,
                'precio' => 0, // En un buffet el precio suele ser 0 o fijo, pero lo dejamos preparado
                'cantidad' => 1,
                'imagen' => $plato->imagen
            ];
        }

        // 4. Guardamos el carrito actualizado de vuelta en la memoria de Laravel
        session()->put('carrito', $carrito);

        // 5. Actualizamos el contador del puntito naranja de notificaciones
        // (Contamos cuántos platos totales hay sumando las cantidades)
        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        session()->put('carrito_count', $totalItems);

        // 6. Devolvemos al cliente a la carta con un mensajito de éxito
        return back()->with('success', $plato->nombre . ' añadido al pedido.');
    }
    public function index()
    {
        // 1. Leemos el carrito de la sesión (si está vacío, devolvemos un array vacío)
        $carrito = session('carrito', []);

        // 2. Recuperamos la sesión actual para poder pintar el número de mesa arriba
        $sesion = Sesion::with('mesa')->find(session('sesion_id'));

        // 3. Mandamos los datos a la vista que vamos a crear
        return view('cliente.carrito', compact('carrito', 'sesion'));
    }
    public function eliminar($id)
    {
        $carrito = SESSION()->get('carrito', []);
        if (isset($carrito[$id])) {

            if ($carrito[$id]['cantidad'] > 1) {
                $carrito[$id]['cantidad']--;
            } else {
                unset($carrito[$id]);
            }
        }

        session()->put('carrito', $carrito);

        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        session()->put('carrito_count', $totalItems);

        return back()->with('success', 'Plato eliminado del pedido.');


    }
}

