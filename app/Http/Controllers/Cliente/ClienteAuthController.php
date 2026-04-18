<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Cliente\AccesoClienteRequest;
use App\Models\Sesion;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\MesaAccesoService;


class ClienteAuthController extends Controller
{
    /**
     * Muestra la pantalla de ingreso (código QR / PIN).
     */
    // Esto va dentro de la clase, arriba de todo

    public function create()
    {
        try {
            // Si el cliente ya tiene una sesión activa en su navegador, lo mandamos a la carta
            if (session()->has('sesion_id')) {
                return redirect()->route('cliente.carta');
            }

            // Usamos 'cliente.ingreso' asumiendo que es el nombre de tu vista Blade
            return view('cliente.ingreso');

        } catch (\Exception $e) {
            Log::error('Error al cargar la vista de acceso cliente: ' . $e->getMessage());
            return response()->view('errors.minimal', ['message' => 'Error al cargar la página'], 500);
        }
    }



    public function store(AccesoClienteRequest $request, MesaAccesoService $mesaAccesoService)
    {
        try {

            $cliente = $mesaAccesoService->unirse($request->codigo, $request->nombre);


            session([
                'sesion_id' => $cliente->sesion_id,
                'cliente_id' => $cliente->id,
                'nombre' => $cliente->nombre,
            ]);

            return redirect()->route('cliente.carta')->with('success', "¡Bienvenido, {$cliente->nombre}!");

        } catch (\Exception $e) {
            return back()->withErrors(['codigo' => $e->getMessage()])->onlyInput('codigo', 'nombre');
        }
    }

    /**
     * Elimina los datos del navegador del cliente .
     */
    public function destroy(Request $request)
    {
        try {
            $request->session()->forget(['sesion_id', 'cliente_id', 'carrito', 'carrito_count']);

            return redirect()->route('cliente.inicio');
        } catch (\Exception $e) {
            Log::error('Error al cerrar sesión del cliente local: ' . $e->getMessage());
            return redirect()->route('cliente.inicio');
        }
    }
}