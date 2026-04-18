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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMesaRequest $request)
    {
        try {
            // Usamos validated() en lugar de all() por seguridad
            Mesa::create($request->validated());

            return back()->with('success', 'Mesa creada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al crear mesa: ' . $e->getMessage());
            return back()->with('error', 'Hubo un problema al crear la mesa.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesa $mesa)
    {
        try {
            // Seguridad extra: comprobamos si tiene sesiones EN CURSO
            $ocupada = $mesa->sesiones()
                ->whereIn('estado', ['activa', 'solicitando_cuenta'])
                ->exists();

            if ($ocupada) {
                return back()->with('error', "¡No puedes borrar la Mesa {$mesa->numero} porque está ocupada!");
            }

            // Usamos una transacción porque vamos a borrar muchos datos relacionados
            DB::transaction(function () use ($mesa) {
                foreach ($mesa->sesiones as $sesion) {
                    foreach ($sesion->pedidos as $pedido) {
                        $pedido->platos()->detach();
                        $pedido->delete();
                    }
                    $sesion->clientes()->delete();
                    $sesion->delete();
                }

                $mesa->delete();
            });

            return back()->with('success', "Mesa {$mesa->numero} y su historial eliminados.");

        } catch (\Exception $e) {
            Log::error('Error al eliminar mesa: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al eliminar la mesa y su historial.');
        }
    }

    // --- MÉTODOS PERSONALIZADOS (ACCIONES) ---

    /**
     * Activa una mesa generando una nueva sesión.
     */
    public function activar(Request $request, Mesa $mesa)
    {
        try {
            // Prevenir activar una mesa que ya está activa
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