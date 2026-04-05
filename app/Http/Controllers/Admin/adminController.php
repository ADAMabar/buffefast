<?php
namespace App\Http\Controllers\Admin;

use App\Models\Mesa;
use App\Models\Sesion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    public function indexMesas()
    {
        // Traemos todas las mesas junto con su sesión activa (si la tienen)
        $mesas = Mesa::with([
            'sesiones' => function ($query) {
                $query->whereIn('estado', ['activa', 'solicitando_cuenta'])->latest();
            }
        ])->orderBy('numero')->get();

        // Traemos las mesas libres para el modal de mesas libres
        $mesasLibres = Mesa::whereDoesntHave('sesiones', function ($query) {
            $query->whereIn('estado', ['activa', 'solicitando_cuenta']);
        })->get();



        return view('admin.mesas', compact('mesas', 'mesasLibres'));
    }

    // El método para abrir una mesa y generar el código
    public function activarMesa(Request $request, Mesa $mesa)
    {
        // Generar un código alfanumérico aleatorio de 6 caracteres
        $codigo = strtoupper(Str::random(6));

        Sesion::create([
            'mesa_id' => $mesa->id,
            'codigo' => $codigo,
            'estado' => 'activa',
        ]);

        return back()->with('success', "Mesa {$mesa->numero} activada. Código de acceso: {$codigo}");
    }
    public function eliminar($id)
    {
        // 1. Buscamos la mesa
        $mesa = Mesa::findOrFail($id);

        // 2. Seguridad extra: comprobamos si tiene sesiones activas
        if ($mesa->sesiones()->count() > 0) {
            return back()->with('error', '¡No puedes borrar la Mesa ' . $mesa->numero . ' porque está ocupada!');
        }

        // 3. Si está libre, la borramos
        $mesa->delete();

        // 4. Devolvemos a la vista principal
        return back()->with('success', 'Mesa ' . $mesa->numero . ' eliminada correctamente.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|min:1|unique:mesas,numero',
            'capacidad' => 'required|integer|min:1',
        ], [
            'numero.required' => 'El número de la mesa es obligatorio.',
            'numero.integer' => 'El número de la mesa debe ser un número entero.',
            'numero.min' => 'El número de la mesa debe ser mayor o igual a 1.',
            'numero.unique' => 'La mesa ya existe.',
        ]);

        Mesa::create($request->all());

        return back()->with('success', 'Mesa creada correctamente.');
    }
}