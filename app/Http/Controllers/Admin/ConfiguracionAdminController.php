<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ConfiguracionAdminController extends Controller
{
    // ====================================================================
    // VISTA PRINCIPAL
    // Carga todos los ajustes como array clave=>valor y la lista de empleados
    // ====================================================================

    public function index()
    {
        try {
            // Convertimos la tabla configuracion a array para usar $ajustes['clave'] en Blade
            $ajustes = Configuracion::pluck('valor', 'clave')->toArray();
            $empleados = Usuario::orderBy('rol')->orderBy('nombre')->get();

            return view('admin.configuracion', compact('ajustes', 'empleados'));

        } catch (\Exception $e) {
            Log::error('Error cargando configuración: ' . $e->getMessage());
            return redirect()->route('admin.mesas')
                ->with('error', 'No se pudo cargar el panel de configuración.');
        }
    }

    // ====================================================================
    // GUARDAR AJUSTES GLOBALES
    // Recibe todos los campos del form y los guarda en la tabla configuracion
    // ====================================================================

    public function updateAjustes(Request $request)
    {
        $request->validate([
            'nombre_restaurante' => 'required|string|max:120',
            'eslogan' => 'nullable|string|max:200',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email_contacto' => 'nullable|email|max:150',
            'color_primario' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_secundario' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo_url' => 'nullable|url|max:500',
            'instagram' => 'nullable|string|max:100',
            'google_maps_url' => 'nullable|url|max:500',
            'wifi_nombre' => 'nullable|string|max:100',
            'wifi_clave' => 'nullable|string|max:100',
            'tiempo_ronda_minutos' => 'required|integer|min:0|max:120',
            'limite_platos_ronda' => 'required|integer|min:1|max:50',
            'rondas_maximas_sesion' => 'required|integer|min:0|max:20',
            'precio_penalizacion' => 'nullable|numeric|min:0',
            'mensaje_penalizacion' => 'nullable|string|max:255',
            'precio_buffet_adulto' => 'required|numeric|min:0',
            'precio_buffet_nino' => 'required|numeric|min:0',
            'precio_buffet_bebe' => 'required|numeric|min:0',
            'porcentaje_impuestos' => 'required|numeric|min:0|max:100',
            'texto_ticket_pie' => 'nullable|string|max:255',
            'alerta_amarilla_min' => 'required|integer|min:1|max:60',
            'alerta_roja_min' => 'required|integer|min:1|max:120',
            'pedidos_servidos_visibles' => 'required|integer|min:0|max:100',
            'refresco_cocina_seg' => 'required|integer|min:5|max:120',
            'tipo_sonido' => 'nullable|in:campana,chime,beep,silencio',
            'mensaje_bienvenida' => 'nullable|string|max:255',
            'mensaje_pedido_confirmado' => 'nullable|string|max:255',
            'mensaje_cuenta_solicitada' => 'nullable|string|max:255',
            'aviso_alergenos' => 'nullable|string|max:500',
            'aviso_legal_carta' => 'nullable|string|max:500',
            'mensaje_cierre_temporal' => 'nullable|string|max:255',
            'intentos_codigo_erroneo' => 'required|integer|min:1|max:20',
            'expiracion_sesion_min' => 'required|integer|min:30|max:1440',
            'longitud_codigo_mesa' => 'required|in:4,5,6,8',
            'email_notificaciones_admin' => 'nullable|email|max:150',
        ]);

        try {
            // Recogemos todos los valores excepto el token CSRF
            $datos = $request->except('_token');

            // Los checkboxes NO se envían en el request cuando están desactivados.
            // Forzamos 'true' si vienen, 'false' si no vienen.
            $toggles = [
                'penalizacion_activa',
                'aceptacion_automatica',
                'sonido_cocina',
                'cocina_mostrar_nombre_cliente',
                'mostrar_precios_carta',
                'mostrar_historial_cliente',
                'permitir_solicitar_cuenta',
                'alergenos_aviso_visible',
                'mostrar_wifi_redes',
                'modo_mantenimiento',
                'bloqueo_ip_activo',
                'registro_log_pedidos',
                'notificacion_email_admin',
                'pago_efectivo',
                'pago_tarjeta',
                'pago_bizum',
                'bypass_temporizador',
                'cliente_puede_cancelar',
            ];

            foreach ($toggles as $toggle) {
                $datos[$toggle] = $request->has($toggle) ? 'true' : 'false';
            }

            // Guardamos cada clave-valor en la tabla configuracion
            DB::transaction(function () use ($datos) {
                foreach ($datos as $clave => $valor) {
                    if ($valor !== null) {
                        Configuracion::updateOrCreate(
                            ['clave' => $clave],
                            ['valor' => (string) $valor]
                        );
                    }
                }
            });

            return back()->with('success', '✓ Configuración guardada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error guardando ajustes: ' . $e->getMessage());
            return back()
                ->with('error', 'Ocurrió un error al guardar. Inténtalo de nuevo.')
                ->withInput();
        }
    }

    // ====================================================================
    // MODO PÁNICO — Toggle AJAX
    // Devuelve JSON para actualizar el botón sin recargar la página
    // ====================================================================

    public function togglePanico()
    {
        try {
            $config = Configuracion::firstOrCreate(
                ['clave' => 'modo_panico'],
                ['valor' => 'false']
            );

            $nuevoEstado = $config->valor === 'true' ? 'false' : 'true';
            $config->update(['valor' => $nuevoEstado]);

            return response()->json([
                'success' => true,
                'estado' => $nuevoEstado,
                'mensaje' => $nuevoEstado === 'true'
                    ? '¡Modo pánico activado! Los clientes no pueden hacer nuevos pedidos.'
                    : 'Pedidos reactivados. Los clientes pueden volver a pedir.',
            ]);

        } catch (\Exception $e) {
            Log::error('Error en modo pánico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'mensaje' => 'Error interno. Inténtalo de nuevo.',
            ], 500);
        }
    }

    // ====================================================================
    // EMPLEADOS — Crear nuevo usuario (admin o cocina)
    // ====================================================================

    public function storeEmpleado(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'rol' => 'required|in:admin,cocina',
            'email' => 'required|email|max:150|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Usuario::create([
                    'nombre' => $request->nombre,
                    'rol' => $request->rol,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
            });

            return back()->with('success', "Empleado '{$request->nombre}' creado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error creando empleado: ' . $e->getMessage());
            return back()
                ->with('error', 'No se pudo crear el empleado. Inténtalo de nuevo.')
                ->withInput($request->except(['password', 'password_confirmation']));
        }
    }

    // ====================================================================
    // EMPLEADOS — Eliminar usuario
    // Un admin no puede borrarse a sí mismo
    // ====================================================================

    public function destroyEmpleado(Usuario $empleado)
    {
        try {
            if (auth()->id() === $empleado->id) {
                return back()->with('error', 'No puedes eliminar tu propia cuenta.');
            }

            $nombre = $empleado->nombre;
            $empleado->delete();

            return back()->with('success', "Empleado '{$nombre}' eliminado del sistema.");

        } catch (\Exception $e) {
            Log::error("Error eliminando empleado {$empleado->id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el empleado.');
        }
    }

    // ====================================================================
    // AJUSTES — Resetear a valores de fábrica
    // Sobreescribe todos los parámetros con los defaults originales
    // ====================================================================

    public function resetearDefecto()
    {
        try {
            $defaults = [
                'tiempo_ronda_minutos' => '10',
                'limite_platos_ronda' => '4',
                'rondas_maximas_sesion' => '0',
                'penalizacion_activa' => 'false',
                'precio_penalizacion' => '2.00',
                'aceptacion_automatica' => 'true',
                'sonido_cocina' => 'true',
                'modo_panico' => 'false',
                'alerta_amarilla_min' => '10',
                'alerta_roja_min' => '20',
                'refresco_cocina_seg' => '15',
                'pedidos_servidos_visibles' => '15',
                'precio_buffet_adulto' => '15.90',
                'precio_buffet_nino' => '8.90',
                'precio_buffet_bebe' => '0.00',
                'porcentaje_impuestos' => '10',
                'pago_efectivo' => 'true',
                'pago_tarjeta' => 'true',
                'pago_bizum' => 'false',
                'longitud_codigo_mesa' => '6',
                'expiracion_sesion_min' => '240',
                'intentos_codigo_erroneo' => '5',
                'bloqueo_ip_activo' => 'true',
                'mostrar_precios_carta' => 'false',
                'mostrar_historial_cliente' => 'true',
                'permitir_solicitar_cuenta' => 'true',
                'alergenos_aviso_visible' => 'true',
            ];

            DB::transaction(function () use ($defaults) {
                foreach ($defaults as $clave => $valor) {
                    Configuracion::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
                }
            });

            return back()->with('success', 'Sistema restaurado a los valores de fábrica.');

        } catch (\Exception $e) {
            Log::error('Error reseteando configuración: ' . $e->getMessage());
            return back()->with('error', 'No se pudieron restaurar los valores por defecto.');
        }
    }
}