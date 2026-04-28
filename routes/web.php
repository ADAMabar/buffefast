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
use App\Http\Controllers\Admin\PlatoAdminController;
use App\Http\Controllers\Admin\CategoriaAdminController;
use App\Http\Controllers\Admin\ConfiguracionAdminController;
use App\Http\Controllers\Admin\HistorialVentasController;

use App\Http\Controllers\Cocina\CocinaController;


/*
|--------------------------------------------------------------------------
| RUTAS DEL CLIENTE (MÓVIL)
|--------------------------------------------------------------------------
*/

Route::get('/', [ClienteAuthController::class, 'create'])->name('cliente.inicio');

// Si el usuario copia y pega la URL (GET), lo mandamos de vuelta al inicio
Route::get('/acceder', function () {
    return redirect()->route('cliente.inicio')->with('error', 'Por favor, inicia sesión usando el formulario.');
});

// Tu ruta original que procesa el formulario (POST)
Route::post('/acceder', [ClienteAuthController::class, 'store'])->name('cliente.acceder');
Route::get('/logout-cliente', [ClienteAuthController::class, 'destroy'])->name('cliente.logout');

Route::get('/carta', [MenuController::class, 'index'])->name('cliente.carta');
Route::get('/cuenta', [CuentaController::class, 'index'])->name('cliente.cuenta');
  Route::post('/cuenta/pedir', [CuentaController::class, 'pedirCuenta'])->name('cliente.cuenta.pedir');

Route::get('/carrito', [CarritoController::class, 'index'])->name('cliente.carrito');
Route::post('/carrito/add/{id}', [CarritoController::class, 'store'])->name('cliente.carrito.add');
Route::post('/carrito/destroy/{id}', [CarritoController::class, 'destroy'])->name('cliente.carrito.destroy');
Route::post('/carrito/confirmar', [CarritoController::class, 'confirmar'])->name('cliente.carrito.confirmar');
Route::get('/nosotros', [CuentaController::class, 'indexSobreNosotros'])->name('cliente.nosotros');

/*
|--------------------------------------------------------------------------
| MÓDULO STAFF (ADMINISTRACIÓN Y COCINA)
|--------------------------------------------------------------------------
*/

// ==========================================
//  SIN LOGUEAR (Público para el Staff)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});


// ==========================================
// PROTEGIDAS (Solo personal logueado)
// ==========================================
Route::middleware('auth')->group(function () {

    // Cerrar Sesión del Staff (Cualquiera logueado puede salir)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // --------------------------------------------------------
    //  ZONA VIP: SOLO ADMINISTRADORES
    // --------------------------------------------------------
    Route::middleware(['can:es-admin'])->group(function () {

        // --- Rutas del Administrador (Mesas) ---
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.mesas');
        Route::post('/admin/mesa/store', [AdminController::class, 'store'])->name('admin.mesa.store');
        Route::post('/admin/mesa/eliminar/{mesa}', [AdminController::class, 'destroy'])->name('admin.mesa.eliminar');
        Route::post('/mesa/{mesa}/activar', [AdminController::class, 'activar'])->name('admin.mesa.activar');

        // --- Módulo: TPV Y SESIONES ---
        Route::get('/admin/mesa/lista-mesas-libres', [ClienteAdminController::class, 'listaMesasLibres'])->name('admin.mesa.lista-mesas-libres');
        Route::get('/admin/mesa/{mesa}/tpv', [ClienteAdminController::class, 'show'])->name('admin.mesa.show');
        Route::post('/admin/mesa/{mesa}/desocupar', [ClienteAdminController::class, 'desocupar'])->name('admin.mesa.desocupar');

        // --- Módulo: CATEGORÍAS Y PLATOS ---
        Route::get('/admin/platos', [PlatoAdminController::class, 'index'])->name('admin.platos.index');
        Route::post('/admin/plato/{plato}/toggle', [PlatoAdminController::class, 'toggleActivo'])->name('admin.plato.toggle');
        Route::post('/admin/categoria/store', [CategoriaAdminController::class, 'store'])->name('admin.categoria.store');
        Route::post('/admin/categoria/{categoria}/eliminar', [CategoriaAdminController::class, 'destroy'])->name('admin.categoria.eliminar');
        Route::post('/admin/carta/plato/store', [PlatoAdminController::class, 'store'])->name('admin.plato.store');
        Route::delete('/admin/carta/plato/{plato}/eliminar', [PlatoAdminController::class, 'destroy']);
        Route::patch('/admin/carta/plato/{plato}/reactivar', [PlatoAdminController::class, 'reactivar'])->name('admin.plato.reactivar');
        Route::put('/admin/carta/plato/{id}/actualizar', [PlatoAdminController::class, 'update'])->name('admin.plato.update');
        // --- Rutas de Historial de Ventas ---
        Route::get('/historial', [HistorialVentasController::class, 'index'])->name('admin.historial');
        Route::post('/admin/mesa/{mesa}/cobrar', [HistorialVentasController::class, 'cobrar'])->name('admin.mesa.cobrar');

        // (El prefijo que tenías para ventas y anulación de tickets)
        Route::prefix('admin')->group(function () {
            Route::get('/ventas/{venta}', [HistorialVentasController::class, 'show'])->name('admin.ventas.show');
            Route::patch('/ventas/{venta}/anular', [HistorialVentasController::class, 'anular'])->name('admin.ventas.anular');
        });

        // --- MÓDULO DE CONFIGURACIÓN Y PERSONAL ---
        Route::get('/admin/configuracion', [ConfiguracionAdminController::class, 'index'])->name('admin.configuracion.index');
        Route::post('/admin/configuracion/ajustes', [ConfiguracionAdminController::class, 'updateAjustes'])->name('admin.configuracion.ajustes');
        Route::post('/admin/configuracion/resetear', [ConfiguracionAdminController::class, 'resetearDefecto'])->name('admin.configuracion.resetear');

    });


   
    Route::middleware(['can:es-cocina'])->group(function () {
    Route::get('/cocina', [CocinaController::class, 'inicio'])->name('cocina.inicio');
    Route::get('/cocina/ver-tablero', [CocinaController::class, 'verTablero'])->name('cocina.verTablero');
    Route::post('/cocina/pedido/{pedido}/actualizar-estado', [CocinaController::class, 'actualizarEstado'])->name('cocina.actualizarEstado');     
    });

});

//lo tengo que borrar cuando cree el cerrar sesion de cocina
Route::get('/salida-de-emergencia', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});


    