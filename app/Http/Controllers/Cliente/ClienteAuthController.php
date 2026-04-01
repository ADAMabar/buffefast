<?php
namespace App\Http\Controllers\Cliente;

use App\Models\Sesion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ClienteAuthController extends Controller
{
    public function mostrarIngreso()
    {
        // Si el cliente ya tiene una sesión activa en su navegador, lo mandamos a la carta
        if (session()->has('sesion_id')) {
            return redirect()->route('cliente.carta');
        }

        return view('cliente.ingreso');
    }

    public function ingresar(Request $request)
    {
        $request->validate([
            'codigo' => ['required', 'string', 'max:6'],
        ], [
            'codigo.required' => 'Por favor, introduce el código de tu mesa.',
        ]);

        // Buscamos una sesión activa que coincida con el código
        $sesion = Sesion::where('codigo', $request->codigo)
            ->where('estado', 'activa')
            ->first();

        if ($sesion) {
            // Guardamos el ID de la sesión en el navegador del cliente
            session(['sesion_id' => $sesion->id]);
            return redirect()->route('cliente.carta');
        }
        return back()->withErrors([
            'codigo' => 'Código inválido o sesión finalizada. Consulta con el camarero.',
        ])->onlyInput('codigo');
    }
}