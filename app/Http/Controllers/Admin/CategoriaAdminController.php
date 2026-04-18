<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorias; // Mantenemos tu nombre en plural para no romper tus modelos
use App\Http\Requests\Admin\StoreCategoriaRequest;
use Illuminate\Support\Facades\Log;

class CategoriaAdminController extends Controller
{
    /**
     * Guarda una nueva categoría.
     */
    public function store(StoreCategoriaRequest $request)
    {
        try {
            Categorias::create([
                'nombre' => $request->nombre,
                'orden' => $request->orden
            ]);

            return back()->with('success', 'Categoría creada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear categoría: ' . $e->getMessage());
            return back()->with('error', 'Hubo un problema al crear la categoría.');
        }
    }

    /**
     * Elimina una categoría comprobando que no tenga platos.
     */
    public function destroy(Categorias $categoria)
    {
        try {
            // Doble seguridad: Por si intentan saltarse el bloqueo del HTML
            if ($categoria->platos()->count() > 0) {
                return back()->with('error', 'No puedes eliminar una categoría que contiene platos.');
            }

            $categoria->delete();

            return back()->with('success', 'Categoría eliminada.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar categoría {$categoria->id}: " . $e->getMessage());
            return back()->with('error', 'Hubo un error al eliminar la categoría.');
        }
    }
}