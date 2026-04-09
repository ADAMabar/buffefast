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