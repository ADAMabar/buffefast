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
use App\Http\Controllers\Admin\ConfiguracionAdminController;


/*
|--------------------------------------------------------------------------
| RUTAS DEL CLIENTE (MÓVIL)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('cliente.ingreso');
})->name('cliente.inicio');

Route::post('/acceder', [ClienteAuthController::class, 'acceder'])->name('cliente.acceder');
Route::get('/logout-cliente', [ClienteAuthController::class, 'logout'])->name('cliente.logout');

Route::get('/carta', [MenuController::class, 'index'])->name('cliente.carta');
Route::get('/cuenta', [CuentaController::class, 'index'])->name('cliente.cuenta');

Route::get('/carrito', [CarritoController::class, 'index'])->name('cliente.carrito');
Route::post('/carrito/add/{id}', [CarritoController::class, 'agregar'])->name('cliente.carrito.add');
Route::post('/carrito/remove/{id}', [CarritoController::class, 'eliminar'])->name('cliente.carrito.remove');
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
    // Rutas del Administrador (TPV)
    // ----------------------------------------
    Route::get('/dashboard', [AdminController::class, 'indexMesas'])->name('admin.mesas');
    Route::post('/admin/mesa/store', [AdminController::class, 'store'])->name('admin.mesa.store');
    Route::post('/admin/mesa/eliminar/{id}', [AdminController::class, 'eliminar'])->name('admin.mesa.eliminar');
    Route::post('/mesa/{mesa}/activar', [AdminController::class, 'activarMesa'])->name('admin.mesa.activar');
    Route::get('/admin/mesa/{id}/tpv', [ClienteAdminController::class, 'show'])->name('admin.mesa.show');
    Route::post('/admin/mesa/{id}/desocupar', [ClienteAdminController::class, 'desocupar'])->name('admin.mesa.desocupar');
    Route::get('/admin/mesa/lista-mesas-libres', [ClienteAdminController::class, 'listaMesasLibres'])->name('admin.mesa.lista-mesas-libres');
    Route::get('/admin/platos', [PlatoAdminController::class, 'index'])->name('admin.platos.index');
    Route::post('/admin/plato/{id}/toggle', [PlatoAdminController::class, 'toggleActivo'])->name('admin.plato.toggle');
    Route::post('/admin/categoria/store', [PlatoAdminController::class, 'storeCategoria'])->name('admin.categoria.store');
    Route::post('/admin/categoria/{id}/eliminar', [PlatoAdminController::class, 'eliminarCategoria'])->name('admin.categoria.eliminar');
    Route::post('/admin/carta/plato/store', [PlatoAdminController::class, 'storePlato'])->name('admin.plato.store');
    Route::delete('/admin/carta/plato/{id}/eliminar', [PlatoAdminController::class, 'destroy']);
    Route::patch('/admin/carta/plato/{id}/reactivar', [PlatoAdminController::class, 'reactivarPlato'])->name('admin.plato.reactivar');


    // ----------------------------------------
    // Rutas de Cocina
    // ----------------------------------------
    Route::get('/cocina', [CocinaController::class, 'index'])->name('cocina.index');
    Route::patch('/cocina/pedido/{pedido}/estado', [CocinaController::class, 'cambiarEstado'])->name('cocina.pedido.estado');


    // ==========================================
    // MÓDULO DE CONFIGURACIÓN Y PERSONAL
    // ==========================================
    Route::prefix('admin/configuracion')->name('admin.configuracion.')->group(function () {

        // 1. Cargar la vista principal con las pestañas
        Route::get('/', [ConfiguracionAdminController::class, 'index'])->name('index');

        // 2. Guardar los ajustes del buffet (Formulario Tab 1)
        Route::post('/ajustes', [ConfiguracionAdminController::class, 'updateAjustesGlobales'])->name('ajustes');

        // 3. Botón del Pánico (Llamada AJAX)
        Route::post('/panico', [ConfiguracionAdminController::class, 'toggleModoPanico'])->name('panico');

        // 4. Gestión de Personal (Modal Tab 2)
        Route::post('/empleado/nuevo', [ConfiguracionAdminController::class, 'storeEmpleado'])->name('empleado.store');

        // 5. Editar y Borrar empleados
        Route::put('/empleado/{id}/editar', [ConfiguracionAdminController::class, 'updateEmpleado'])->name('empleado.update');
        Route::delete('/empleado/{id}/eliminar', [ConfiguracionAdminController::class, 'destroyEmpleado'])->name('empleado.destroy');

    });
});