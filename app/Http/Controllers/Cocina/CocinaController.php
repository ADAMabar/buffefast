<?php

namespace App\Http\Controllers\Cocina;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class CocinaController extends Controller
{
    public function inicio()
    {
        return view('cocina.principal');
    }

    public function verTablero()
    {
        $pedidos = Pedido::with(['sesion.mesa', 'platos'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('cocina.fragmentos.tablero', [
            'pendientes' => $pedidos->where('estado', 'pendiente'),
            'preparando' => $pedidos->where('estado', 'preparando'),
            'servidos'   => $pedidos->where('estado', 'servido')->sortByDesc('updated_at')->take(15),
        ]);
    }

    public function actualizarEstado(Request $request, Pedido $pedido)
    {
        $nuevoEstado = $request->estado;

        if ($pedido->estado === 'pendiente' && $nuevoEstado === 'servido') {
            return response()->json(['success' => false, 'message' => 'Orden de estados incorrecta'], 400);
        }

        $pedido->update(['estado' => $nuevoEstado]);

        return response()->json(['success' => true]);
    }
}