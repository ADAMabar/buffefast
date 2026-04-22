<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use App\Models\Usuario;

class ConfiguracionAdminController extends Controller
{
    /**
     * Muestra el panel de configuración con todas las variables cargadas.
     */
    public function index()
    {
        // Traemos todos los ajustes y los pasamos a formato ['clave' => 'valor']
        $ajustes = Configuracion::pluck('valor', 'clave')->toArray();
        
        // Traemos a los empleados que sean admin o de cocina
       $empleados = Usuario::whereIn('rol', ['admin', 'cocina'])->get();

       $seciones = Configuracion::All();

        return view('admin.configuracion', compact('ajustes', 'empleados'));
    }
    public function storeEmpleado(Request $request)
    {
    $request->validate([
            'nombre' => 'required|string|max:255',
            // OJO AQUÍ: cambiamos unique:users por unique:usuarios
            'email' => 'required|email|unique:usuarios,email', 
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:admin,cocina',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);
        return redirect()->back()->with('success', 'Empleado creado correctamente.');
    }
    public function resetearDefecto()
    {
        // Borramos toda la tabla de configuración
        Configuracion::truncate();
        
        // Volvemos a llamar al Seeder para poblarla de cero
        Artisan::call('db:seed', ['--class' => 'ConfiguracionSeeder']);
        
        cache()->forget('ajustes_globales');

        return redirect()->back()->with('success', 'Valores de fábrica restaurados con éxito.');
    }

    public function updateAjustes(Request $request)
    {
        // Excluimos el token CSRF y variables que no van a la BD directamente
        $datos = $request->except(['_token', 'porcentaje_impuestos_custom']);

        // Truco para el IVA personalizado: si seleccionaron "otro", guardamos el valor custom
        if ($request->porcentaje_impuestos === 'otro' && $request->filled('porcentaje_impuestos_custom')) {
            $datos['porcentaje_impuestos'] = $request->porcentaje_impuestos_custom;
        }

       
        $checkboxes = [
            'penalizacion_activa', 
            'pago_efectivo', 'pago_tarjeta', 'pago_bizum',
            'sonido_cocina', 'cocina_mostrar_nombre_cliente', 'mostrar_precios_carta',
            'mostrar_historial_cliente', 'permitir_solicitar_cuenta', 'alergenos_aviso_visible',
            'mostrar_wifi_redes', 'modo_mantenimiento', 'bloqueo_ip_activo',
            'registro_log_pedidos', 'notificacion_email_admin'
        ];

        foreach ($checkboxes as $chk) {
            $datos[$chk] = $request->has($chk) ? 'true' : 'false';
        }

        // Guardamos cada valor en la base de datos
        foreach ($datos as $clave => $valor) {
            Configuracion::where('clave', $clave)->update(['valor' => $valor]);
        }

        // Limpiamos la caché (Esto es vital para el Paso 3 del Helper)
        cache()->forget('ajustes_globales');

        return redirect()->back()->with('success', 'Configuración guardada correctamente.');
    }

    public function destroyEmpleado(Usuario $empleado)
    {
        if (auth()->id() === $empleado->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $empleado->delete();

        return redirect()->back()->with('success', 'Empleado eliminado.');
    }


}
