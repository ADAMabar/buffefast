<?php

use Illuminate\Support\Facades\Route;

// Controladores Cliente
use App\Http\Controllers\Cliente\ClienteAuthController;
use App\Http\Controllers\Cliente\MenuController;
use App\Http\Controllers\Cliente\CarritoController;
use App\Http\Controllers\Cliente\CuentaController;

// Controladores Staff (Admin/Cocina)
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClienteAdminController;
use App\Http\Controllers\Cocina\CocinaController;
use App\Http\Controllers\Admin\PlatoAdminController;
use App\Http\Controllers\Admin\CategoriaAdminController;
use App\Http\Controllers\Admin\ConfiguracionAdminController;
use App\Http\Controllers\Admin\HistorialVentasController;

/*
|--------------------------------------------------------------------------
| RUTAS DEL CLIENTE (MÓVIL)
|--------------------------------------------------------------------------
*/

Route::get('/', [ClienteAuthController::class, 'create'])->name('cliente.inicio');

Route::post('/acceder', [ClienteAuthController::class, 'store'])->name('cliente.acceder');
Route::get('/logout-cliente', [ClienteAuthController::class, 'destroy'])->name('cliente.logout');

Route::get('/carta', [MenuController::class, 'index'])->name('cliente.carta');
Route::get('/cuenta', [CuentaController::class, 'index'])->name('cliente.cuenta');

Route::get('/carrito', [CarritoController::class, 'index'])->name('cliente.carrito');
Route::post('/carrito/add/{id}', [CarritoController::class, 'store'])->name('cliente.carrito.add');
Route::post('/carrito/destroy/{id}', [CarritoController::class, 'destroy'])->name('cliente.carrito.destroy');
Route::post('/carrito/confirmar', [CarritoController::class, 'confirmar'])->name('cliente.carrito.confirmar');


/*
|--------------------------------------------------------------------------
| MÓDULO STAFF (ADMINISTRACIÓN Y COCINA)
|--------------------------------------------------------------------------
*/

// Sin loguear
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Protegidas (Solo personal logueado)
Route::middleware('auth')->group(function () {

    // Cerrar Sesión del Staff
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ----------------------------------------
    // Rutas del Administrador (Mesas)
    // ----------------------------------------
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.mesas');
    Route::post('/admin/mesa/store', [AdminController::class, 'store'])->name('admin.mesa.store');
    Route::post('/admin/mesa/eliminar/{mesa}', [AdminController::class, 'destroy'])->name('admin.mesa.eliminar');
    Route::post('/mesa/{mesa}/activar', [AdminController::class, 'activar'])->name('admin.mesa.activar');

    // ----------------------------------------
    // Módulo: TPV Y SESIONES
    // ----------------------------------------
    Route::get('/admin/mesa/lista-mesas-libres', [ClienteAdminController::class, 'listaMesasLibres'])->name('admin.mesa.lista-mesas-libres');
    Route::get('/admin/mesa/{mesa}/tpv', [ClienteAdminController::class, 'show'])->name('admin.mesa.show');
    Route::post('/admin/mesa/{mesa}/desocupar', [ClienteAdminController::class, 'desocupar'])->name('admin.mesa.desocupar');

    // ----------------------------------------
    // Módulo: CATEGORÍAS Y PLATOS (Restauradas sin Resource)
    // ----------------------------------------
    Route::get('/admin/platos', [PlatoAdminController::class, 'index'])->name('admin.platos.index');
    Route::post('/admin/plato/{plato}/toggle', [PlatoAdminController::class, 'toggleActivo'])->name('admin.plato.toggle');
    Route::post('/admin/categoria/store', [CategoriaAdminController::class, 'store'])->name('admin.categoria.store');
    Route::post('/admin/categoria/{categoria}/eliminar', [CategoriaAdminController::class, 'destroy'])->name('admin.categoria.eliminar');
    Route::post('/admin/carta/plato/store', [PlatoAdminController::class, 'store'])->name('admin.plato.store');
    Route::delete('/admin/carta/plato/{plato}/eliminar', [PlatoAdminController::class, 'destroy']);
    Route::patch('/admin/carta/plato/{plato}/reactivar', [PlatoAdminController::class, 'reactivar'])->name('admin.plato.reactivar');


    // ----------------------------------------
    // Rutas de Cocina
    // ----------------------------------------
    Route::get('/cocina', [CocinaController::class, 'index'])->name('cocina.index');
    Route::patch('/cocina/pedido/{pedido}/estado', [CocinaController::class, 'cambiarEstado'])->name('cocina.pedido.estado');

    // ----------------------------------------
    // Rutas de Historial de Ventas
    // ----------------------------------------
    Route::get('/historial', [historialVentasController::class, 'index'])->name('admin.historial');
    Route::post('/admin/mesa/{mesa}/cobrar', [historialVentasController::class, 'cobrar'])->name('admin.mesa.cobrar');

    // 2. Pega esto dentro de tu grupo de rutas del Panel de Admin (Route::middleware(['auth'])...)
    Route::prefix('admin')->group(function () {

        // El Panel Principal del Historial (También maneja la exportación a CSV)
        Route::get('/historial', [HistorialVentasController::class, 'index'])->name('admin.historial');

        // La ruta mágica para que el Modal cargue el ticket sin recargar la página (AJAX)
        Route::get('/ventas/{venta}', [HistorialVentasController::class, 'show'])->name('admin.ventas.show');

        // La ruta para cuando el cajero la lía y tiene que anular un ticket
        Route::patch('/ventas/{venta}/anular', [HistorialVentasController::class, 'anular'])->name('admin.ventas.anular');

    });

    // ==========================================
    // MÓDULO DE CONFIGURACIÓN Y PERSONAL
    // ==========================================
    Route::middleware(['auth'])->group(function () {
        //
//     // Panel de configuración (GET)
        Route::get('/admin/configuracion', [ConfiguracionAdminController::class, 'index'])
            ->name('admin.configuracion.index');
        //
        // Guardar ajustes del form global (POST)
        Route::post('/admin/configuracion/ajustes', [ConfiguracionAdminController::class, 'updateAjustes'])
            ->name('admin.configuracion.ajustes');

        //     // Modo pánico AJAX (POST → devuelve JSON)
        Route::post('/admin/configuracion/panico', [ConfiguracionAdminController::class, 'togglePanico'])
            ->name('admin.configuracion.panico');
        //
//     // Restaurar valores de fábrica (POST)
        Route::post('/admin/configuracion/resetear', [ConfiguracionAdminController::class, 'resetearDefecto'])
            ->name('admin.configuracion.resetear');
        //
//     // Crear empleado (POST)
        Route::post('/admin/configuracion/empleado', [ConfiguracionAdminController::class, 'storeEmpleado'])
            ->name('admin.configuracion.empleado.store');
        //
//     // Eliminar empleado (DELETE, usa Route Model Binding)
        Route::delete('/admin/configuracion/empleado/{empleado}', [ConfiguracionAdminController::class, 'destroyEmpleado'])
            ->name('admin.configuracion.empleado.destroy');
        //
    });


    // ============================================================
// CAMPOS EN LA TABLA `configuracion` QUE SE USAN
// (para que sepas qué claves espera el sistema)
// ============================================================
//
// Identidad:
//   nombre_restaurante, eslogan, direccion, telefono, email_contacto
//   color_primario, color_secundario, logo_url
//   instagram, google_maps_url, wifi_nombre, wifi_clave
//
// Operativa:
//   tiempo_ronda_minutos, limite_platos_ronda, rondas_maximas_sesion
//   penalizacion_activa, precio_penalizacion, mensaje_penalizacion
//   bypass_temporizador, cliente_puede_cancelar
//
// Precios:
//   precio_buffet_adulto, precio_buffet_nino, precio_buffet_bebe
//   porcentaje_impuestos, texto_ticket_pie
//   pago_efectivo, pago_tarjeta, pago_bizum
//
// Cocina:
//   alerta_amarilla_min, alerta_roja_min, pedidos_servidos_visibles
//   sonido_cocina, tipo_sonido, refresco_cocina_seg
//   cocina_mostrar_nombre_cliente, aceptacion_automatica
//
// App Cliente:
//   mensaje_bienvenida, mensaje_pedido_confirmado, mensaje_cuenta_solicitada
//   aviso_alergenos, aviso_legal_carta
//   mostrar_precios_carta, mostrar_historial_cliente, permitir_solicitar_cuenta
//   alergenos_aviso_visible, mostrar_wifi_redes
//   modo_mantenimiento, mensaje_cierre_temporal
//
// Seguridad:
//   longitud_codigo_mesa, expiracion_sesion_min, intentos_codigo_erroneo
//   bloqueo_ip_activo, registro_log_pedidos, notificacion_email_admin
//   email_notificaciones_admin
//
// Sistema:
//   modo_panico


});