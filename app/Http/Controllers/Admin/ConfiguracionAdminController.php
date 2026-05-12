<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Requests\Admin\StoreEmpleadoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
 
        // Procesar logo si se subió uno nuevo
        if ($request->hasFile('logo')) {
            $archivo = $request->file('logo');
            
            // Validar tipo de archivo
            $extension = $archivo->getClientOriginalExtension();
            $permitidos = ['png', 'svg', 'jpg', 'jpeg'];
            
            if (in_array(strtolower($extension), $permitidos)) {
                // Generar nombre único
                $nombreArchivo = 'logo_' . time() . '.' . $extension;
                
                // Guardar en storage/public/logos
                $ruta = $archivo->storeAs('logos', $nombreArchivo, 'public');
                
                // Guardar URL en datos
                $datos['logo_url'] = '/storage/' . $ruta;
            }
        } elseif ($request->filled('logo_actual')) {
            // Mantener logo anterior si no se subió nuevo
            $datos['logo_url'] = $request->logo_actual;
        }
 
        // Guardamos cada valor en la base de datos
        foreach ($datos as $clave => $valor) {
            Configuracion::where('clave', $clave)->update(['valor' => $valor]);
        }
 
        cache()->forget('ajustes_globales');
 
        return redirect()->back()->with('success', 'Configuración guardada correctamente.');
    }


}