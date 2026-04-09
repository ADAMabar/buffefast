<?php
namespace App\Http\Controllers\Admin;

use App\Models\Mesa;
use App\Models\Sesion;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreMesaRequest;
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

        // 2. Seguridad extra: comprobamos si tiene sesiones EN CURSO (activa o cuenta)
        $ocupada = $mesa->sesiones()
            ->whereIn('estado', ['activa', 'solicitando_cuenta'])
            ->exists();

        if ($ocupada) {
            return back()->with('error', '¡No puedes borrar la Mesa ' . $mesa->numero . ' porque está ocupada!');
        }

        // 3. Para que la DB nos deje borrarla (integridad referencial), limpiamos su rastro
        // Recorremos todas las sesiones pasadas (cerradas)
        foreach ($mesa->sesiones as $sesion) {
            // Borramos los pedidos de esa sesión y sus relaciones en la tabla intermedia
            foreach ($sesion->pedidos as $pedido) {
                $pedido->platos()->detach(); // Borra en pedido_platos
                $pedido->delete();           // Borra en pedidos
            }
            // Borramos los clientes de esa sesión
            $sesion->clientes()->delete();
            // Borramos la sesión
            $sesion->delete();
        }

        // 4. Finalmente, borramos la mesa
        $mesa->delete();

        // 5. Devolvemos a la vista principal
        return back()->with('success', 'Mesa ' . $mesa->numero . ' y todo su historial eliminados correctamente.');
    }

    public function store(StoreMesaRequest $request)
    {

        Mesa::create($request->all());

        return back()->with('success', 'Mesa creada correctamente.');
    }
}