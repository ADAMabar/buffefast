<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// Modelos
use App\Models\Usuario; // Usaste Usuario en tu código, si es User, cámbialo a User
use App\Models\Configuracion;
use App\Models\Mesa;
use App\Models\Sesion;
use App\Models\Pedido;
// Requests
use App\Http\Requests\Admin\UpdateAjustesRequest;
use App\Http\Requests\Admin\StoreEmpleadoRequest;
use App\Http\Requests\Admin\UpdateEmpleadoRequest;
use App\Http\Requests\Admin\ExportarDatosRequest;

class ConfiguracionAdminController extends Controller
{
    /* ====================================================================
     * 1. VISTAS DEL PANEL DE CONTROL
     * ==================================================================== */

    public function index()
    {
        $ajustes = Configuracion::pluck('valor', 'clave')->toArray();
        $empleados = Usuario::orderBy('rol')->get();

        return view('admin.configuracion', compact('ajustes', 'empleados'));
    }

    public function empleadosIndex()
    {
        // Si el día de mañana quieres una pantalla solo para empleados, la cargarías aquí.
        $empleados = Usuario::orderBy('rol')->paginate(15);
        return view('admin.empleados.index', compact('empleados'));
    }


    /* ====================================================================
     * 2. AJUSTES DEL BUFFET Y SISTEMA
     * ==================================================================== */

    public function updateAjustesGlobales(UpdateAjustesRequest $request)
    {
        // 1. Recogemos los datos validados
        $datos = $request->validated();

        // Truco: Los checkbox (booleanos) no se envían si están desmarcados. 
        // Forzamos su valor a '0' si no vienen en la request.
        $booleanos = ['penalizacion_activa', 'aceptacion_automatica', 'sonido_cocina'];
        foreach ($booleanos as $bool) {
            $datos[$bool] = $request->has($bool) ? '1' : '0';
        }

        // 2. Guardamos todo de golpe con una transacción
        DB::transaction(function () use ($datos) {
            foreach ($datos as $clave => $valor) {
                if ($valor !== null) {
                    Configuracion::updateOrCreate(
                        ['clave' => $clave],
                        ['valor' => $valor]
                    );
                }
            }
        });

        return back()->with('success', 'Configuración del buffet actualizada con éxito.');
    }

    public function resetearAjustesPorDefecto()
    {
        $valoresPorDefecto = [
            'tiempo_ronda_minutos' => '10',
            'limite_platos_ronda' => '4',
            'penalizacion_activa' => '0',
            'precio_penalizacion' => '2.00',
            'nombre_restaurante' => 'BuffetFast',
            'porcentaje_impuestos' => '10',
            'aceptacion_automatica' => '1',
            'sonido_cocina' => '1',
            'modo_panico' => 'false'
        ];

        DB::transaction(function () use ($valoresPorDefecto) {
            foreach ($valoresPorDefecto as $clave => $valor) {
                Configuracion::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
            }
        });

        return back()->with('success', 'El sistema ha vuelto a los ajustes de fábrica.');
    }


    /* ====================================================================
     * 3. GESTIÓN DE PERSONAL
     * ==================================================================== */

    public function storeEmpleado(StoreEmpleadoRequest $request)
    {
        DB::transaction(function () use ($request) {
            Usuario::create([
                'nombre' => $request->nombre,
                'rol' => $request->rol,
                'email' => $request->email,
                'password' => $request->filled('password') ? Hash::make($request->password) : null,
                'pin' => $request->pin,
            ]);
        });

        return back()->with('success', 'Nuevo empleado registrado correctamente.');
    }

    public function updateEmpleado(UpdateEmpleadoRequest $request, $id)
    {
        $empleado = Usuario::findOrFail($id);

        DB::transaction(function () use ($request, $empleado) {
            $empleado->nombre = $request->nombre;
            $empleado->rol = $request->rol;

            // Casuística: Si lo cambiamos a camarero, le borramos email/pass y le ponemos PIN
            if ($request->rol === 'camarero') {
                $empleado->pin = $request->pin;
                $empleado->email = null;
                $empleado->password = null;
            } else {
                // Casuística: Si es admin/cocinero, le borramos el PIN
                $empleado->email = $request->email;
                $empleado->pin = null;

                // Solo cambiamos la contraseña si escribió una nueva
                if ($request->filled('password')) {
                    $empleado->password = Hash::make($request->password);
                }
            }

            $empleado->save();
        });

        return back()->with('success', 'Datos del empleado actualizados.');
    }

    public function destroyEmpleado($id)
    {
        // Casuística Estrella: No te borres a ti mismo
        if (auth()->id() == $id) {
            return back()->withErrors(['Error crítico' => 'No puedes eliminar tu propia cuenta de Administrador.']);
        }

        $empleado = Usuario::findOrFail($id);
        $empleado->delete();

        return back()->with('success', 'Empleado eliminado del sistema.');
    }

    public function regenerarPinCamarero($id)
    {
        $empleado = Usuario::findOrFail($id);

        if ($empleado->rol !== 'camarero') {
            return back()->withErrors(['Solo los camareros utilizan código PIN.']);
        }

        // Genera un PIN aleatorio de 4 dígitos que no esté ya en uso
        do {
            $nuevoPin = rand(1000, 9999);
        } while (Usuario::where('pin', $nuevoPin)->exists());

        $empleado->update(['pin' => $nuevoPin]);

        return back()->with('success', "El nuevo PIN para {$empleado->nombre} es: {$nuevoPin}");
    }

    public function toggleModoPanico()
    {
        $config = Configuracion::firstOrCreate(
            ['clave' => 'modo_panico'],
            ['valor' => 'false']
        );

        $nuevoEstado = $config->valor === 'true' ? 'false' : 'true';
        $config->update(['valor' => $nuevoEstado]);

        return response()->json([
            'success' => true,
            'message' => $nuevoEstado === 'true' ? 'MODO PÁNICO ACTIVADO.' : 'Pedidos reactivados.',
            'estado' => $nuevoEstado
        ]);
    }

    public function forzarCierreJornada()
    {
        // Casuística: Son las 3:00 AM, el local cierra y quedaron mesas "abiertas" por error.
        DB::transaction(function () {
            // 1. Finalizamos todas las sesiones que sigan activas
            Sesion::where('estado', 'activa')->update(['estado' => 'finalizada']);

            // 2. Liberamos todas las mesas
            Mesa::where('estado', 'ocupada')->update(['estado' => 'libre']);
        });

        return back()->with('success', 'Jornada cerrada: Todas las mesas han sido liberadas.');
    }

    public function exportarDatosMensuales(ExportarDatosRequest $request)
    {
        $pedidos = Pedido::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])->get();

        $filename = "facturacion_buffet_" . date('Y-m-d') . ".csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID Pedido', 'Mesa', 'Total', 'Fecha']);

        foreach ($pedidos as $pedido) {
            fputcsv($handle, [$pedido->id, $pedido->sesion->mesa->numero, $pedido->total, $pedido->created_at]);
        }
        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}