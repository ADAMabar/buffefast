<?php
namespace App\Http\Controllers\Cliente;

use App\Models\Sesion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;

class ClienteAuthController extends Controller
{
    public function mostrarIngreso()
    {
        // Si el cliente ya tiene una sesión activa en su navegador, lo mandamos a la carta
        if (session()->has('sesion_id')) {
            return redirect()->route('cliente.carta');
        }

        return view('cliente.acceso');
    }

    public function acceder(Request $request)
    {
        $request->validate([
            'codigo' => ['required', 'string', 'max:6'],
            'nombre' => ['required', 'string', 'max:50'],
        ], [
            'codigo.required' => 'Por favor, introduce el código de tu mesa.',
            'nombre.required' => 'Por favor, dinos tu nombre.',
        ]);

        // Buscamos una sesión activa que coincida con el código
        $sesion = Sesion::where('codigo', $request->codigo)
            ->where('estado', 'activa')
            ->first();

        if ($sesion) {
            $cliente = Cliente::create([
                'nombre' => $request->nombre,
                'sesion_id' => $sesion->id
            ]);

            // Guardamos el ID de la sesión y del cliente en el navegador
            session([
                'sesion_id' => $sesion->id,
                'cliente_id' => $cliente->id
            ]);

            return redirect()->route('cliente.carta');
        }
        return back()->withErrors([
            'codigo' => 'Código inválido o sesión finalizada. Consulta con el camarero.',
        ])->onlyInput('codigo', 'nombre');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['sesion_id', 'cliente_id', 'carrito', 'carrito_count']);
        return redirect()->route('cliente.inicio');
    }
}