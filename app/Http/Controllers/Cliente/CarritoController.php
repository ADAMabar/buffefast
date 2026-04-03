<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plato;
use App\Models\Sesion;
use App\Models\Pedido;
use App\Models\Configuracion;

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
        $sesion_id = session('sesion_id');

        // 2. Recuperamos la sesión actual para poder pintar el número de mesa arriba
        $sesion = Sesion::with('mesa')->find(session('sesion_id'));

        $minutosEspera = (int) (Configuracion::where('clave', 'minutos_entre_rondas')->value('valor') ?? 10);

        // Buscamos el último pedido de esta mesa
        $ultimoPedido = Pedido::where('sesion_id', $sesion_id)
            ->latest()
            ->first();

        $segundosRestantes = 0;

        if ($ultimoPedido) {
            // Calculamos la diferencia entre "ahora" y "el momento en que pidió + el tiempo de espera"
            $tiempoPermitido = $ultimoPedido->created_at->addMinutes($minutosEspera);
            $ahora = now();

            if ($ahora->lt($tiempoPermitido)) {
                $segundosRestantes = $ahora->diffInSeconds($tiempoPermitido);
            }
        }

        return view('cliente.carrito', compact('carrito', 'sesion', 'segundosRestantes'));

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







    public function confirmar()
    {
        //Aqui me aseguro que aunque toquen el script desde la consola del navegador, no se vea afectado el contador
        $sesion_id = session('sesion_id');
        $minutosEspera = (int) (Configuracion::where('clave', 'minutos_entre_rondas')->value('valor') ?? 10);

        $ultimoPedido = Pedido::where('sesion_id', $sesion_id)->latest()->first();

        if ($ultimoPedido && now()->lt($ultimoPedido->created_at->addMinutes($minutosEspera))) {
            return back()->with('error', 'Aún debes esperar un poco para la siguiente ronda.');
        }



        $cliente_id = session('cliente_id');
        $sesion_id = session('sesion_id');
        // 1. Leemos el carrito. Si está vacío por algún error, le devolvemos.
        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return back()->with('error', 'No puedes enviar un pedido vacío.');
        }
        // Contamos los pedidos que ya existen para esta sesión y sumamos 1
        $rondaActual = Pedido::where('sesion_id', $sesion_id)->count() + 1;

        // Creamos el registro principal en la tabla `pedidos` (AHORA SÍ CON TODO)
        $pedido = Pedido::create([
            'cliente_id' => $cliente_id,
            'sesion_id' => $sesion_id,
            'ronda' => $rondaActual,
            'estado' => 'pendiente'
        ]);
        // Recorremos el carrito y usamos attach() para guardar en la tabla intermedia `pedido_platos`
        foreach ($carrito as $idPlato => $detalles) {
            $pedido->platos()->attach($idPlato, [
                'cantidad' => $detalles['cantidad']
            ]);
        }

        // 5. El pedido ya está en la Base de Datos. ¡Vaciamos la mochila temporal!
        session()->forget('carrito');
        session()->forget('carrito_count');

        // 6. Redirigimos a la carta con un mensaje de celebración
        return redirect()->route('cliente.carta')->with('success', '¡Marchando! Tu pedido ya está en la cocina.');
    }
}

