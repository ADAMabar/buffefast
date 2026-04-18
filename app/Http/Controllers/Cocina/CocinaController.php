<?php
namespace App\Http\Controllers\Cocina;

use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Http\Requests\Cocina\CambiarEstadoPedidoRequest;
use App\Http\Controllers\Controller;

class CocinaController extends Controller
{
    public function index()
    {
        // Traemos los pedidos con sus relaciones para evitar el problema N+1
        $pedidos = Pedido::with(['sesion.mesa', 'detalles.plato'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Agrupamos en memoria (más rápido que hacer 3 consultas a la BD)
        $pendientes = $pedidos->where('estado', 'pendiente');
        $preparando = $pedidos->where('estado', 'preparando');

        // Solo traemos los últimos 15 servidos para no saturar la vista
        $servidos = $pedidos->where('estado', 'servido')->sortByDesc('updated_at')->take(15);

        return view('cocina.index', compact('pendientes', 'preparando', 'servidos'));
    }

    public function cambiarEstado(CambiarEstadoPedidoRequest $request, Pedido $pedido)
    {
        // Reglas de validación delegadas al Form Request
        $validated = $request->validated();

        // Regla de Negocio: No se puede saltar de pendiente a servido directamente (opcional, pero buena práctica)
        if ($pedido->estado === 'pendiente' && $validated['estado'] === 'servido') {
            return back()->withErrors(['error' => 'Debes pasar por "En Preparación" primero.']);
        }

        $pedido->update(['estado' => $validated['estado']]);

        return back()->with('success', 'Pedido actualizado correctamente.');
    }
}
/*
<?php

namespace App\Http\Controllers\Cocina;

use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Http\Requests\Cocina\CambiarEstadoPedidoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CocinaController extends Controller
{
    /**
     * Muestra el panel (Kanban) de la cocina.

    public function index()
    {
        try {
            // 1. Pedidos Activos: Le pedimos a la BD que filtre directamente.
            // OJO: Usamos 'platos' en vez de 'detalles.plato' asumiendo que usas belongsToMany
            $pedidosActivos = Pedido::with(['sesion.mesa', 'platos'])
                ->whereIn('estado', ['pendiente', 'preparando'])
                ->orderBy('created_at', 'asc')
                ->get();

            // Los separamos en memoria porque ya son poquitos (solo los que se están cocinando)
            $pendientes = $pedidosActivos->where('estado', 'pendiente');
            $preparando = $pedidosActivos->where('estado', 'preparando');

            // 2. Pedidos Servidos: Le pedimos a la BD que traiga SOLO los 15 más recientes.
            $servidos = Pedido::with(['sesion.mesa', 'platos'])
                ->where('estado', 'servido')
                ->orderBy('updated_at', 'desc') // Ordenamos por el momento en que se sirvieron
                ->limit(15)                     // Equivalente a take(15) pero a nivel de SQL
                ->get();

            return view('cocina.index', compact('pendientes', 'preparando', 'servidos'));

        } catch (\Exception $e) {
            Log::error('Error al cargar la pantalla de cocina: ' . $e->getMessage());
            // Si eres Admin/Cocinero, te mandamos al dashboard si esto falla
            return redirect()->route('admin.mesas')->with('error', 'Ocurrió un error al cargar la vista de cocina.');
        }
    }

    /**
     * Actualiza el estado de un pedido (Botones o Drag & Drop).

    public function cambiarEstado(CambiarEstadoPedidoRequest $request, Pedido $pedido)
    {
        try {
            $validated = $request->validated();

            // Regla de Negocio: No se puede saltar de pendiente a servido directamente
            if ($pedido->estado === 'pendiente' && $validated['estado'] === 'servido') {
                return back()->withErrors(['error' => 'Debes pasar por "En Preparación" primero.']);
            }

            // Actualizamos el estado
            $pedido->update(['estado' => $validated['estado']]);

            return back()->with('success', 'Pedido actualizado correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al cambiar el estado del pedido {$pedido->id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo actualizar el estado del pedido.');
        }
    }
}*/