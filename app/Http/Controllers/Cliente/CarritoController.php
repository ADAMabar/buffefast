<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plato;
use App\Models\Sesion;
use App\Models\Pedido;
use App\Models\Configuracion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarritoController extends Controller
{
    /**
     * Muestra la vista del carrito actual y el temporizador.
     */
    public function index()
    {
        try {
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

            // 2. Leemos el carrito y la sesión actual
            $carrito = session('carrito', []);
            $cliente_id = session('cliente_id');
            $sesion = Sesion::with('mesa')->find($sesion_id);

            // 3. Lógica del temporizador (Anti-spam de comandas)
            $minutosEspera = (int) (Configuracion::where('clave', 'minutos_entre_rondas')->value('valor') ?? 0);
            $ultimoPedido = Pedido::where('sesion_id', $sesion_id)->where('cliente_id', $cliente_id)->latest()->first();
            $segundosRestantes = 0;

            if ($ultimoPedido) {
                $tiempoPermitido = $ultimoPedido->created_at->addMinutes($minutosEspera);
                $ahora = now();

                if ($ahora->lt($tiempoPermitido)) {
                    $segundosRestantes = $ahora->diffInSeconds($tiempoPermitido);
                }
            }

            $rondaActual = Pedido::where('sesion_id', $sesion_id)->count() + 1;

            return view('cliente.carrito', compact('carrito', 'sesion', 'segundosRestantes', 'rondaActual'));

        } catch (\Exception $e) {
            Log::error('Error al cargar el carrito del cliente: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al cargar tu pedido.');
        }
    }

    /**
     * Añade un plato al carrito en la sesión (Sustituye a 'agregar').
     */
    public function store($id)
    {
        try {
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

        } catch (\Exception $e) {

            return back()->with('error', 'No se pudo añadir el plato al pedido.');
        }
    }

    /**
     * Resta 1 o elimina completamente un plato del carrito (Sustituye a 'eliminar').
     */
    public function destroy($id)
    {
        try {
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

        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo retirar el plato.');
        }
    }

    /**
     * Confirma el carrito, crea el pedido en BD y lo manda a cocina.
     */
    public function confirmar()
    {
        try {
            $sesion_id = session('sesion_id');
            $cliente_id = session('cliente_id');


            // 1. Validar carrito vacío
            $carrito = session()->get('carrito', []);
            if (empty($carrito)) {
                return back()->with('error', 'No puedes enviar un pedido vacío.');
            }

            // 2. Anti-cheat de tiempo (Si tocan el frontend con inspeccionar elemento)
            $minutosEspera = (int) (Configuracion::where('clave', 'minutos_entre_rondas')->value('valor') ?? 10);
            $ultimoPedido = Pedido::where('sesion_id', $sesion_id)->where('cliente_id', $cliente_id)->latest()->first();

            if ($ultimoPedido && now()->lt($ultimoPedido->created_at->addMinutes($minutosEspera))) {
                return back()->with('error', 'Aún debes esperar un poco para la siguiente ronda.');
            }

            // 3. LA MAGIA: Transacción para asegurar el guardado perfecto
            DB::transaction(function () use ($sesion_id, $cliente_id, $carrito) {

                // Contamos la ronda bloqueando la tabla temporalmente para evitar peticiones duplicadas simultáneas
                $rondaActual = Pedido::where('sesion_id', $sesion_id)
                    ->where('cliente_id', $cliente_id)
                    ->lockForUpdate()
                    ->count() + 1;

                // Creamos el registro "Padre"
                $pedido = Pedido::create([
                    'cliente_id' => $cliente_id,
                    'sesion_id' => $sesion_id,
                    'ronda' => $rondaActual,
                    'estado' => 'pendiente'
                ]);

                // Guardamos los platos en la tabla intermedia
                foreach ($carrito as $idPlato => $detalles) {
                    $pedido->platos()->attach($idPlato, [
                        'cantidad' => $detalles['cantidad']
                    ]);
                }
            });

            // 4. Limpiamos la memoria tras el éxito
            session()->forget(['carrito', 'carrito_count']);

            return redirect()->route('cliente.carta')->with('success', '¡Marchando! Tu pedido ya está en la cocina.');

        } catch (\Exception $e) {
            Log::error('Error crítico al confirmar el pedido: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al procesar tu pedido. Por favor, inténtalo de nuevo.');
        }
    }
}
