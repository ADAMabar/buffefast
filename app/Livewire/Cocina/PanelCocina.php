<?php

namespace App\Livewire\Cocina;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Pedido;
#[Layout('layouts.app')]
class PanelCocina extends Component
{
    public $verOcultos = false;

    public function toggleVerOcultos()
    {
        $this->verOcultos = !$this->verOcultos;
    }

    public function restaurarPedido($id)
    {
        $pedido = Pedido::find($id);
        if ($pedido) {
            $pedido->update(['visible_cocina' => true]);
        }
    }
    
    public function actualizarEstado($id, $nuevoEstado)
    {
        $pedido = Pedido::find($id);

        if ($pedido) {
            // Aquí conservamos tu validación original
            if ($pedido->estado === 'pendiente' && $nuevoEstado === 'servido') {
                // En lugar de devolver un JSON 400, simplemente detenemos la acción.
                // (Opcional: Podrías usar $this->js('alert("Orden incorrecta")'); para avisar al usuario)
                return; 
            }

            // Actualizamos el estado
            $pedido->update(['estado' => $nuevoEstado]);
        }
    }

    /**
     * Este método es llamado por el botón "Limpiar" en la columna de Listos.
     */
    public function limpiarListos()
    {
      Pedido::where('estado', 'servido')
              ->where('visible_cocina', true)
              ->update(['visible_cocina' => false]);
              $this->verOcultos = false;
    }

  
    #[Computed]
    public function pendientes()
    {
        return Pedido::with(['sesion.mesa', 'platos'])
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    #[Computed]
    public function preparando()
    {
        return Pedido::with(['sesion.mesa', 'platos'])
            ->where('estado', 'preparando')
            ->orderBy('created_at', 'asc')
            ->get();
    }

   #[Computed]
    public function servidos()
    {
        $query = Pedido::with(['sesion.mesa', 'platos'])->where('estado', 'servido');

        if ($this->verOcultos) {
            return $query->where('visible_cocina', false)->orderBy('updated_at', 'desc')->take(10)->get();
        }

        return $query->where('visible_cocina', true)->orderBy('updated_at', 'desc')->take(15)->get();
    }

    public function render()
    {
        // Apuntamos a la vista dentro de la subcarpeta 'cocina'
        return view('livewire.cocina.panel-cocina');
    }
}