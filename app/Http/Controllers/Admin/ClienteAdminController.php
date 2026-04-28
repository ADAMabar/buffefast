<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sesion;
use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Caja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cliente;

class ClienteAdminController extends Controller
{
    /**
     * Muestra el panel TPV (detalles y pedidos) de una mesa ocupada.
     */
    public function show(Mesa $mesa)
    {
        try {
            // 1. Buscamos la sesión activa
            $sesionActiva = $mesa->sesiones()
                ->whereIn('estado', ['activa', 'solicitando_cuenta'])
                ->latest()
                ->first();

            if (!$sesionActiva) {
                return redirect()->route('admin.mesas')->with('error', "La Mesa {$mesa->numero} está libre actualmente.");
            }

            // 2. Recuperamos pedidos
            $pedidos = Pedido::with('platos', 'cliente')
                ->where('sesion_id', $sesionActiva->id)
                ->orderBy('ronda', 'desc')
                ->get();

            // 3. AGRUPAMOS Y MAPEAMOS (Eliminé el duplicado que tenías)
            $pedidosAgrupados = $pedidos->groupBy(function ($pedido) {
                // EXORCISMO 1: Usamos ?-> para evitar que explote si el cliente es null
                return $pedido->cliente?->nombre ?? 'Anónimo';
            })->map(function ($pedidosDelCliente) {

                $totalPersona = $pedidosDelCliente->sum(function ($pedido) {
                    return $pedido->platos->sum(function ($plato) {
                        // Validamos que exista el pivot y la cantidad por seguridad
                        $cantidad = $plato->pivot->cantidad ?? 1;
                        return $plato->precio * $cantidad;
                    });
                });

                return [
                    'historial_pedidos' => $pedidosDelCliente,
                    'total_euros' => $totalPersona,
                    'cantidad_rondas' => $pedidosDelCliente->count()
                ];
            });
           
          $porcentajeIva = configuracion('porcentaje_impuestos', 10);
            $multiplicadorIva = 1 + ($porcentajeIva / 100);

            $precioBase = Configuracion('precio_buffet_adulto',10);
            $totalMenu = $pedidos->sum(function($pedido) use ($multiplicadorIva) {
                return $pedido->platos->sum(function($plato) use ($multiplicadorIva) {
                    
                    $subtotal = $plato->precio * $plato->pivot->cantidad;
                    return $subtotal * $multiplicadorIva; 
                    
                });
            });

            $totalMesa = $totalMenu + $precioBase;  

            $cajas = Caja::where('activa', true)->get();

            return view('admin.detalles-mesa', [
                'mesa' => $mesa,
                'sesionActiva' => $sesionActiva,
                'pedidosAgrupados' => $pedidosAgrupados,
                'pedidos' => $pedidos,
                'totalMesa' => $totalMesa,
                'cajas' => $cajas,
            ]);

            // Cambié \Exception por \Throwable para atrapar errores fatales de PHP 
        } catch (\Throwable $e) {
            Log::error("Error al cargar TPV de la mesa {$mesa->id}: " . $e->getMessage());
            return redirect()->route('admin.mesas')->with('error', 'Ocurrió un error al cargar los detalles de la mesa.');
        }
    }

    /**
     * Cobra y cierra la sesión actual de la mesa, dejándola libre.
     */
    public function desocupar(Mesa $mesa)
    {
        try {
            // 1. Buscamos SU sesión actual en curso
            $sesionActiva = $mesa->sesiones()
                ->whereIn('estado', ['activa', 'solicitando_cuenta'])
                ->first();

            if (!$sesionActiva) {
                return redirect()->route('admin.mesas')->with('warning', 'La mesa ya se encontraba libre.');
            }

            // 2. Transacción: Ideal por si en el futuro decides generar la factura PDF justo aquí
            DB::transaction(function () use ($sesionActiva) {
                $sesionActiva->update([
                    'estado' => 'cerrada'
                ]);
            });

            return redirect()->route('admin.mesas')->with('success', "Mesa {$mesa->numero} cobrada y desocupada.");

        } catch (\Exception $e) {
            Log::error("Error al desocupar la mesa {$mesa->id}: " . $e->getMessage());
            return back()->with('error', 'Hubo un problema al intentar desocupar la mesa.');
        }
    }

    /**
     * Carga el contenido parcial de las mesas libres (ideal para modales o peticiones AJAX).
     */
    public function listaMesasLibres()
    {
        try {
            // Traemos SOLO las mesas que NO tengan sesiones activas o pidiendo cuenta
            $mesasLibres = Mesa::whereDoesntHave('sesiones', function ($query) {
                $query->whereIn('estado', ['activa', 'solicitando_cuenta']);
            })->get();

            return view('admin.modals.listaMesasLibres', compact('mesasLibres'));

        } catch (\Exception $e) {
            Log::error('Error al cargar modal de mesas libres: ' . $e->getMessage());
            // Como esto probablemente se cargue en un modal/AJAX, devolver un error amigable
            return response()->view('errors.minimal', ['message' => 'Error al cargar las mesas'], 500);
        }
    }


    public function notificaciondecuenta(Mesa $mesa)
    {
     $sesionActiva = $mesa->sesiones()      
    ->whereIn('estado', ['activa', 'solicitando_cuenta'])
    ->first();

    $mesa = $sesionActiva->$mesa->pluck('numero');
    $nombresPidiendoCuenta = $sesionActiva->clientes->pluck('nombre')->implode(',');
    
    return view('admin.listaPlatosOcultos', compact('sesionActiva', 'nombresPidiendoCuenta', '$mesa'));

    }
}