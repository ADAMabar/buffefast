<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sesion;
use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Cliente;


class ClienteAdminController extends Controller
{
    public function show($id)
    {
        // 1. Buscamos la mesa
        $mesa = Mesa::findOrFail($id);

        // 2. Buscamos la sesión activa (la que se está usando ahora mismo)
        $sesionActiva = $mesa->sesiones()->latest()->first();

        // Si por algún error intentan entrar a una mesa libre, los devolvemos
        if (!$sesionActiva) {
            return redirect()->route('admin.inicio')->with('error', 'Esa mesa está libre actualmente.');
        }

        // 3. Recuperamos todos los pedidos de esta sesión CON sus platos
        $pedidos = Pedido::with('platos')
            ->where('sesion_id', $sesionActiva->id)
            ->orderBy('ronda', 'desc')
            ->get();

        // 4. Mandamos todo a la vista del TPV
        return view('admin.detalles-mesa', compact('mesa', 'sesionActiva', 'pedidos'));
    }
    public function desocupar($id)
    {
        // 1. Encontramos la mesa
        $mesa = Mesa::findOrFail($id);

        // 2. Buscamos SU sesión actual (la que está 'activa')
        $sesionActiva = $mesa->sesiones()->where('estado', 'activa')->first();

        // 3. Si hay sesión, la cerramos con la palabra EXACTA
        if ($sesionActiva) {
            $sesionActiva->update([
                'estado' => 'cerrada' // <-- ¡Magia pura terminada en A!
            ]);
        }


        // 4. Volvemos al panel principal
        return redirect()->route('admin.mesas')->with('success', 'Mesa' . $mesa->numero . ' cobrada y desocupada.');
    }
    public function listaMesasLibres()
    {
        // Traemos SOLO las mesas que NO tengan sesiones activas o pidiendo cuenta
        $mesasLibres = Mesa::whereDoesntHave('sesiones', function ($query) {
            $query->whereIn('estado', ['activa', 'solicitando_cuenta']);
        })->get();

        // Mandamos los datos a la vista
        return view('admin.modals.listaMesasLibres', compact('mesasLibres'));
    }





}
