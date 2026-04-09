<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorias;
use App\Models\Plato;
use App\Http\Requests\Admin\storeCategoriaRequest;
use App\Http\Requests\Admin\storePlatoRequest;

class PlatoAdminController extends Controller
{
    public function index()
    {

        $categorias = Categorias::with('platos')->get();
        return view('admin.platos', compact('categorias'));
    }

    public function toggleActivo($id)
    {
        $plato = Plato::findOrFail($id);

        // Invertimos el estado (si estaba activado se desactiva, y viceversa)
        $plato->activo = !$plato->activo;
        $plato->save();

        // Creamos un mensaje dinámico para saber qué ha pasado
        $mensaje = $plato->activo ? 'Plato activado y visible en la app.' : 'Plato ocultado de la app.';

        return back()->with('success', $mensaje);
    }

    // --- GESTIÓN DE CATEGORÍAS ---

    public function storeCategoria(storeCategoriaRequest $request)
    {

        Categorias::create([
            'nombre' => $request->nombre,
            'orden' => $request->orden
        ]);

        return back()->with('success', 'Categoría creada correctamente.');
    }

    public function eliminarCategoria($id)
    {
        $categoria = Categorias::findOrFail($id);

        // Doble seguridad: Por si intentan saltarse el bloqueo de HTML
        if ($categoria->platos()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoría que contiene platos.');
        }

        $categoria->delete();

        return back()->with('success', 'Categoría eliminada.');
    }

    public function storePlato(storePlatoRequest $request)
    {
        $rutaImagen = null;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('platos', 'public');
        }

        Plato::create([
            'nombre' => $request->nombre,
            'categoria_id' => $request->categoria_id,
            'descripcion' => $request->descripcion,
            'imagen' => $rutaImagen,
            'activo' => true
        ]);

        return back()->with('success', '¡Plato añadido a la carta con éxito!');
    }

    public function destroy($id)
    {
        $plato = Plato::findOrFail($id);
        $plato->delete();
        return response()->json([
            'success' => true,
            'message' => 'Plato eliminado correctamente'
        ]);
    }

    public function reactivarPlato($id)
    {
        $plato = Plato::findOrFail($id);
        
        $plato->activo = true; // Lo volvemos a encender
        $plato->save();

        return response()->json([
            'success' => true,
            'message' => 'Plato reactivado correctamente'
        ]);
    }

}
