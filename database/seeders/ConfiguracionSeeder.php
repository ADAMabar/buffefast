<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracion;
use Carbon\Carbon;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = Carbon::now();

        $ajustes = [
            // ==========================================
            // TAB 1: IDENTIDAD
            // ==========================================
            ['clave' => 'nombre_restaurante', 'valor' => 'BuffeFast', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Aparece en tickets, cabecera de la app y notificaciones.'],
            ['clave' => 'eslogan', 'valor' => 'Automatiza a tus clientes', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Eslogan promocional del restaurante.'],
            ['clave' => 'direccion', 'valor' => 'C/ Gran Vía 14, Almería', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Dirección física para mostrar en los tickets.'],
            ['clave' => 'telefono', 'valor' => '950 000 000', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Teléfono principal de contacto.'],
            ['clave' => 'email_contacto', 'valor' => 'info@buffefast.com', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Email público del restaurante.'],
            ['clave' => 'logo_url', 'valor' => '', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'URL del logotipo en PNG o SVG.'],
            ['clave' => 'instagram', 'valor' => '', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Usuario de Instagram.'],
            ['clave' => 'google_maps_url', 'valor' => '', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Enlace a la ubicación en Google Maps.'],
            ['clave' => 'wifi_nombre', 'valor' => '', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Nombre de la red WiFi para clientes.'],
            ['clave' => 'wifi_clave', 'valor' => '', 'seccion' => 'identidad', 'tipo' => 'string', 'descripcion' => 'Contraseña del WiFi. Dejar vacío para no mostrar.'],

            // ==========================================
            // TAB 2: OPERATIVA
            // ==========================================
            ['clave' => 'tiempo_ronda_minutos', 'valor' => '10', 'seccion' => 'operativa', 'tipo' => 'integer', 'descripcion' => 'Minutos de espera obligatoria entre rondas (0 = sin límite).'],
            ['clave' => 'limite_platos_ronda', 'valor' => '4', 'seccion' => 'operativa', 'tipo' => 'integer', 'descripcion' => 'Límite de platos que puede pedir una persona por ronda.'],
            ['clave' => 'rondas_maximas_sesion', 'valor' => '0', 'seccion' => 'operativa', 'tipo' => 'integer', 'descripcion' => 'Máximo de rondas permitidas por sesión (0 = ilimitadas).'],
            ['clave' => 'penalizacion_activa', 'valor' => 'false', 'seccion' => 'operativa', 'tipo' => 'boolean', 'descripcion' => 'Habilita el cargo por platos no consumidos.'],
            ['clave' => 'precio_penalizacion', 'valor' => '2.00', 'seccion' => 'operativa', 'tipo' => 'float', 'descripcion' => 'Precio en euros por cada plato sobrante.'],
            ['clave' => 'mensaje_penalizacion', 'valor' => 'Los platos no consumidos tienen un cargo de {precio}€/ud.', 'seccion' => 'operativa', 'tipo' => 'string', 'descripcion' => 'Mensaje de advertencia para el cliente.'],

            // ==========================================
            // TAB 3: PRECIOS Y COBROS
            // ==========================================
            ['clave' => 'precio_buffet_adulto', 'valor' => '15.90', 'seccion' => 'precios', 'tipo' => 'float', 'descripcion' => 'Precio tarifa adulto (+12 años).'],
            ['clave' => 'precio_buffet_nino', 'valor' => '8.90', 'seccion' => 'precios', 'tipo' => 'float', 'descripcion' => 'Precio tarifa niño (4-12 años).'],
            ['clave' => 'precio_buffet_bebe', 'valor' => '0.00', 'seccion' => 'precios', 'tipo' => 'float', 'descripcion' => 'Precio tarifa bebé (0-3 años).'],
            ['clave' => 'porcentaje_impuestos', 'valor' => '10', 'seccion' => 'precios', 'tipo' => 'string', 'descripcion' => 'IVA aplicable a la cuenta final.', 'opciones' => json_encode(['0', '4', '10', '21', 'otro'])],
            ['clave' => 'pago_efectivo', 'valor' => 'true', 'seccion' => 'precios', 'tipo' => 'boolean', 'descripcion' => 'Permitir cobro en efectivo.'],
            ['clave' => 'pago_tarjeta', 'valor' => 'true', 'seccion' => 'precios', 'tipo' => 'boolean', 'descripcion' => 'Permitir cobro con tarjeta.'],
            ['clave' => 'pago_bizum', 'valor' => 'false', 'seccion' => 'precios', 'tipo' => 'boolean', 'descripcion' => 'Permitir cobro con Bizum.'],
            ['clave' => 'texto_ticket_pie', 'valor' => '¡Gracias por su visita!', 'seccion' => 'precios', 'tipo' => 'string', 'descripcion' => 'Mensaje de despedida impreso en el ticket.'],

            // ==========================================
            // TAB 4: COCINA
            // ==========================================
            ['clave' => 'alerta_amarilla_min', 'valor' => '10', 'seccion' => 'cocina', 'tipo' => 'integer', 'descripcion' => 'Minutos para que un ticket cambie a color amarillo (espera moderada).'],
            ['clave' => 'alerta_roja_min', 'valor' => '20', 'seccion' => 'cocina', 'tipo' => 'integer', 'descripcion' => 'Minutos para que un ticket cambie a color rojo (prioridad crítica).'],
            ['clave' => 'pedidos_servidos_visibles', 'valor' => '15', 'seccion' => 'cocina', 'tipo' => 'integer', 'descripcion' => 'Cantidad de pedidos ya servidos que se muestran en el historial.'],
            ['clave' => 'sonido_cocina', 'valor' => 'true', 'seccion' => 'cocina', 'tipo' => 'boolean', 'descripcion' => 'Activa la alerta sonora al entrar pedidos.'],
            ['clave' => 'tipo_sonido', 'valor' => 'campana', 'seccion' => 'cocina', 'tipo' => 'string', 'descripcion' => 'Archivo de audio para la alerta.', 'opciones' => json_encode(['campana', 'chime', 'beep', 'silencio'])],
            ['clave' => 'refresco_cocina_seg', 'valor' => '15', 'seccion' => 'cocina', 'tipo' => 'integer', 'descripcion' => 'Segundos que tarda la tablet de cocina en actualizarse sola.'],
            ['clave' => 'cocina_mostrar_nombre_cliente', 'valor' => 'true', 'seccion' => 'cocina', 'tipo' => 'boolean', 'descripcion' => 'Muestra el nombre del comensal en el ticket de cocina.'],

            // ==========================================
            // TAB 5: APP CLIENTE
            // ==========================================
            ['clave' => 'mensaje_bienvenida', 'valor' => '¡Bienvenido! Introduce el código de tu mesa.', 'seccion' => 'cliente', 'tipo' => 'string', 'descripcion' => 'Texto en la pantalla de inicio del cliente.'],
            ['clave' => 'mensaje_pedido_confirmado', 'valor' => '¡Pedido enviado! Tu ronda está en camino.', 'seccion' => 'cliente', 'tipo' => 'string', 'descripcion' => 'Texto tras lanzar una ronda.'],
            ['clave' => 'mensaje_cuenta_solicitada', 'valor' => 'Un camarero se acercará a tu mesa en breve.', 'seccion' => 'cliente', 'tipo' => 'string', 'descripcion' => 'Texto tras pedir la cuenta.'],
            ['clave' => 'aviso_alergenos', 'valor' => 'Para información sobre alérgenos, consulte con nuestro personal.', 'seccion' => 'cliente', 'tipo' => 'string', 'descripcion' => 'Banner superior en la carta digital.'],
            ['clave' => 'aviso_legal_carta', 'valor' => '', 'seccion' => 'cliente', 'tipo' => 'string', 'descripcion' => 'Texto legal en el pie de la carta digital.'],
            ['clave' => 'mostrar_precios_carta', 'valor' => 'false', 'seccion' => 'cliente', 'tipo' => 'boolean', 'descripcion' => 'Muestra precios individuales de platos (apagar en buffet libre).'],
            ['clave' => 'mostrar_historial_cliente', 'valor' => 'true', 'seccion' => 'cliente', 'tipo' => 'boolean', 'descripcion' => 'El cliente puede ver lo que ha pedido en rondas anteriores.'],
            ['clave' => 'permitir_solicitar_cuenta', 'valor' => 'true', 'seccion' => 'cliente', 'tipo' => 'boolean', 'descripcion' => 'Activa el botón de pedir cuenta desde la app.'],
            ['clave' => 'alergenos_aviso_visible', 'valor' => 'true', 'seccion' => 'cliente', 'tipo' => 'boolean', 'descripcion' => 'Muestra u oculta el banner de alérgenos.'],
            ['clave' => 'mostrar_wifi_redes', 'valor' => 'true', 'seccion' => 'cliente', 'tipo' => 'boolean', 'descripcion' => 'Muestra la tarjeta de WiFi e Instagram al pedir cuenta.'],
            ['clave' => 'modo_mantenimiento', 'valor' => 'false', 'seccion' => 'cliente', 'tipo' => 'boolean', 'descripcion' => 'Bloquea el acceso a nuevos clientes.'],
            ['clave' => 'mensaje_cierre_temporal', 'valor' => 'Volvemos en breve. ¡Gracias por tu paciencia!', 'seccion' => 'cliente', 'tipo' => 'string', 'descripcion' => 'Aviso mostrado cuando el modo mantenimiento está activo.'],

            // ==========================================
            // TAB 6: SEGURIDAD
            // ==========================================
            ['clave' => 'longitud_codigo_mesa', 'valor' => '6', 'seccion' => 'seguridad', 'tipo' => 'integer', 'descripcion' => 'Número de caracteres de los códigos generados para acceder a la mesa.', 'opciones' => json_encode(['4', '5', '6', '8'])],
            ['clave' => 'expiracion_sesion_min', 'valor' => '240', 'seccion' => 'seguridad', 'tipo' => 'integer', 'descripcion' => 'Minutos antes de que la sesión del cliente caduque (240 = 4h).'],
            ['clave' => 'intentos_codigo_erroneo', 'valor' => '5', 'seccion' => 'seguridad', 'tipo' => 'integer', 'descripcion' => 'Veces que un cliente puede equivocarse de código antes de bloqueo.'],
            ['clave' => 'bloqueo_ip_activo', 'valor' => 'true', 'seccion' => 'seguridad', 'tipo' => 'boolean', 'descripcion' => 'Bloquea temporalmente IPs con comportamiento sospechoso.'],
            ['clave' => 'registro_log_pedidos', 'valor' => 'true', 'seccion' => 'seguridad', 'tipo' => 'boolean', 'descripcion' => 'Guarda un histórico detallado para auditoría del sistema.'],
            ['clave' => 'notificacion_email_admin', 'valor' => 'false', 'seccion' => 'seguridad', 'tipo' => 'boolean', 'descripcion' => 'Envía errores críticos al correo del administrador.'],
            ['clave' => 'email_notificaciones_admin', 'valor' => '', 'seccion' => 'seguridad', 'tipo' => 'string', 'descripcion' => 'Correo al que llegarán las notificaciones de seguridad.'],
        ];

        foreach ($ajustes as $ajuste) {
            $opciones = $ajuste['opciones'] ?? null;

            Configuracion::updateOrCreate(
                ['clave' => $ajuste['clave']], 
                [
                    'valor' => $ajuste['valor'],
                    'seccion' => $ajuste['seccion'],
                    'tipo' => $ajuste['tipo'],
                    'descripcion' => $ajuste['descripcion'],
                    'opciones' => $opciones,
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ]
            );
        }
    }
}
