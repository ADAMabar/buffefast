<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductosPago;
use Illuminate\Http\Request;
use App\Models\Categorias;
use App\Models\Plato;
use App\Http\Requests\Admin\StorePlatoRequest;
use Illuminate\Support\Facades\Log;

class PlatoAdminController extends Controller
{
    /**
     * Carga la vista principal de la carta.
     */
    public function index()
    {
        try {
            $categorias = Categorias::with('platos')->get();
            return view('admin.platos', compact('categorias'));
        } catch (\Exception $e) {
            Log::error('Error cargando la vista de la carta: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al cargar la carta.');
        }
    }

    /**
     * Guarda un nuevo plato con su imagen.
     */
    public function store(StorePlatoRequest $request)
    {
        try {
            $rutaImagen = null;
            if ($request->hasFile('imagen')) {
                $rutaImagen = $request->file('imagen')->store('platos', 'public');
            }

            Plato::create([
                'nombre' => $request->nombre,
                'categoria_id' => $request->categoria_id,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'imagen' => $rutaImagen,
                'activo' => true
            ]);

            return back()->with('success', '¡Plato añadido a la carta con éxito!');
        } catch (\Exception $e) {
            Log::error('Error al guardar plato: ' . $e->getMessage());
            return back()->with('error', 'Hubo un problema al añadir el plato.');
        }
    }

    public function destroy(Plato $plato)
    {
        try {
            $plato->delete();

            return response()->json([
                'success' => true,
                'message' => 'Plato eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error("Error al eliminar plato {$plato->id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el plato'], 500);
        }
    }

    // --- ACCIONES PERSONALIZADAS ---

    /**
     * Activa o desactiva la visibilidad de un plato en la app.
     */
    public function toggleActivo(Plato $plato)
    {
        try {
            $plato->activo = !$plato->activo;
            $plato->save();

            $mensaje = $plato->activo ? 'Plato activado y visible en la app.' : 'Plato ocultado de la app.';
            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            Log::error("Error alternando visibilidad del plato {$plato->id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo actualizar el estado del plato.');
        }
    }

    
    public function reactivar(Plato $plato)
    {
        try {
            $plato->activo = true;
            $plato->save();

            return response()->json([
                'success' => true,
                'message' => 'Plato reactivado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error("Error reactivando plato {$plato->id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al reactivar el plato'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // 1. Buscamos el plato en la base de datos
            $plato = Plato::findOrFail($id);

            // 2. Validamos los datos (puedes usar un FormRequest si lo prefieres)
            $request->validate([
                'nombre' => 'required|string|max:255',
                'categoria_id' => 'required|exists:categorias,id',
                'precio' => 'required|numeric|min:0',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            // 3. Preparamos los datos para actualizar
            $datosActualizar = [
                'nombre' => $request->nombre,
                'categoria_id' => $request->categoria_id,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
            ];

            // 4. ¿Ha subido una foto nueva?
            if ($request->hasFile('imagen')) {
                // Borramos la foto vieja del servidor para no acumular basura
                if ($plato->imagen) {
                    Storage::disk('public')->delete($plato->imagen);
                }
                
                // Guardamos la foto nueva y la añadimos a los datos a actualizar
                $datosActualizar['imagen'] = $request->file('imagen')->store('platos', 'public');
            }

            // 5. Actualizamos el plato de golpe
            $plato->update($datosActualizar);

            return back()->with('success', '¡Plato actualizado con éxito!');

        } catch (\Exception $e) {
            Log::error('Error al actualizar plato: ' . $e->getMessage());
            // Si falla, le devolvemos los errores específicos para ese modal
            return back()->withErrors(['edit_plato_'.$id => 'Hubo un problema al actualizar el plato.'])->withInput();
        }
    }
}