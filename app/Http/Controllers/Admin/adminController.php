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

        return view('admin.mesas', compact('mesas'));
    }

    // El método para abrir una mesa y generar el código
    public function activarMesa(Request $request, Mesa $mesa)
    {
        // Generar un código alfanumérico aleatorio de 6 caracteres
        $codigo = strtoupper(Str::random(6));

        // Crear la sesión
        Sesion::create([
            'mesa_id' => $mesa->id,
            'codigo' => $codigo,
            'estado' => 'activa',
        ]);

        return back()->with('success', "Mesa {$mesa->numero} activada. Código de acceso: {$codigo}");
    }
}