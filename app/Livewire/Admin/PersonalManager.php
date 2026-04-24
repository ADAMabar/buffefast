<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Usuario; // Tu modelo
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Caja;

class PersonalManager extends Component
{
    
    public $nombre = '';
    public $email = '';
    public $password = '';
    public $rol = 'cocina';

    public $mensajeExito = '';
    public $mensajeError = '';

    public $nombreCaja = '';
    public $activaCaja = true;
    public $mensajeExitoCaja = '';
    public $mensajeErrorCaja = '';

    protected $rules = [
        'nombre' => 'required|string|max:100',
        'email' => 'required|email|max:150|unique:usuarios,email',
        'password' => 'required|string|min:6',
        'rol' => 'required|in:admin,cocina',
    ];

    public function crearEmpleado()
    {
        
        $this->validate();

        DB::beginTransaction();
        try {
            Usuario::create([
                'nombre' => $this->nombre,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'rol' => $this->rol,
            ]);

            DB::commit();

            $this->reset(['nombre', 'email', 'password']);
            $this->rol = 'cocina';
            $this->mensajeExito = '¡Empleado creado con éxito!';
            $this->mensajeError = '';

        } catch (\Exception $e) {
            DB::rollBack();
            $this->mensajeError = 'Error al crear empleado en la base de datos.';
            $this->mensajeExito = '';
        }
    }

    public function borrarEmpleado($id)
    {
        if (auth()->id() == $id) {
            $this->mensajeError = 'No puedes borrar tu propia cuenta.';
            $this->mensajeExito = '';
            return;
        }

        Usuario::findOrFail($id)->delete();
        $this->mensajeExito = 'Empleado eliminado correctamente.';
        $this->mensajeError = '';
    }

  public function crearCaja()
    {
        
        $this->validate([
            'nombreCaja' => 'required|string|max:255|unique:cajas,nombre',
            'activaCaja' => 'boolean',
        ]);

        try {
            Caja::create([
                'nombre' => $this->nombreCaja,
                'activa' => $this->activaCaja,
            ]);

            $this->reset(['nombreCaja']);
            $this->activaCaja = true;
            $this->mensajeExitoCaja = '¡Caja creada con éxito!';
            $this->mensajeErrorCaja = '';
        } catch (\Exception $e) {
            $this->mensajeErrorCaja = 'Error al crear la caja.';
            $this->mensajeExitoCaja = '';
        }
    }

    public function borrarCaja($id)
    {
        try {
            Caja::findOrFail($id)->delete();
            $this->mensajeExitoCaja = 'Caja eliminada correctamente.';
            $this->mensajeErrorCaja = '';
        } catch (\Exception $e) {
            $this->mensajeErrorCaja = 'No se puede borrar la caja si tiene ventas asociadas.';
            $this->mensajeExitoCaja = '';
        }
    }

    public function toggleCaja($id)
    {
        $caja = Caja::findOrFail($id);
        $caja->activa = !$caja->activa;
        $caja->save();
    }


    public function render()
    {
        return view('livewire.admin.personal-manager', [
            'empleados' => Usuario::orderBy('created_at', 'desc')->get(),
            'cajas' => Caja::orderBy('created_at', 'desc')->get(),
        ]);
    }
}