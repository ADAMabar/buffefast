<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Sesion;
use App\Http\Requests\Admin\StoreMesaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $mesas = Mesa::with([
                'sesiones' => function ($query) {
                    $query->whereIn('estado', ['activa', 'solicitando_cuenta'])->latest();
                }
            ])->orderBy('numero')->get();

            $mesasLibres = Mesa::whereDoesntHave('sesiones', function ($query) {
                $query->whereIn('estado', ['activa', 'solicitando_cuenta']);
            })->get();

            $mesasPidiendoCuenta = Mesa::whereHas('sesiones', function ($query) {
                $query->where('estado', 'solicitando_cuenta');
            })->with([
                        'sesiones' => function ($query) {
                            $query->where('estado', 'solicitando_cuenta')->latest();
                        }
                    ])->get();


            return view('admin.mesas', compact('mesas', 'mesasLibres', 'mesasPidiendoCuenta'));



        } catch (\Exception $e) {
            Log::error('Error cargando el panel de mesas: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al cargar las mesas.');
        }
    }
  
 public function store(StoreMesaRequest $request)
{
    try {
        $reglas = [
            'numero' => 'required|integer|unique:mesas,numero',
            'capacidad' => 'required|integer|min:1',
        ];

        $mensajes = [
            'numero.required' => 'El número de la mesa es obligatorio.',
            'numero.integer'  => 'El número de la mesa debe ser un valor entero.',
            'numero.unique'   => 'Este número de mesa ya está registrado. Por favor, elige otro.',
            'capacidad.required' => 'La capacidad de la mesa es obligatoria.',
            'capacidad.integer'  => 'La capacidad debe ser un número entero.',
            'capacidad.min'      => 'La capacidad debe ser para al menos 1 persona.',
        ];

        $validate = request()->validate($reglas, $mensajes);
        
        Mesa::create($validate);

        return back()->with('success', 'Mesa creada correctamente.');

    } catch (\Exception $e) {
        Log::error('Error al crear mesa: ' . $e->getMessage());
        return back()->with('error', 'Hubo un problema al crear la mesa.');
    }
}

  

public function destroy(Mesa $mesa)
{
    try {
        
        $activa = $mesa->sesiones()
            ->whereIn('estado', ['activa', 'solicitando_cuenta'])
            ->exists();

        if ($activa) {
            return back()->with('error', "¡No puedes borrar la Mesa {$mesa->numero} porque está ocupada!");
        }

        $mesa->sesiones()->update(['mesa_id' => null]);
        $mesa->delete();

        return back()->with('success', "Mesa {$mesa->numero} eliminada. El historial de pedidos se ha conservado.");

    } catch (\Exception $e) {
        Log::error('Error al eliminar mesa: ' . $e->getMessage());
        return back()->with('error', 'Hubo un error al eliminar la mesa.');
    }
}

   
    public function activar(Request $request, Mesa $mesa)
    {
        try {
            
            $ocupada = $mesa->sesiones()
                ->whereIn('estado', ['activa', 'solicitando_cuenta'])
                ->exists();

            if ($ocupada) {
                return back()->with('error', 'La mesa ya se encuentra activa.');
            }

            $codigo = strtoupper(Str::random(6));

            Sesion::create([
                'mesa_id' => $mesa->id,
                'codigo' => $codigo,
                'estado' => 'activa',
            ]);

            return back()->with('success', "Mesa {$mesa->numero} activada. Código de acceso: {$codigo}");

        } catch (\Exception $e) {
            Log::error('Error al activar la mesa: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al generar el código de la mesa.');
        }
    }
}