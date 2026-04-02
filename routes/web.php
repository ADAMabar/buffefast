<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Cocina\CocinaController;
// use App\Http\Controllers\CocinaController; // Lo usaremos cuando le demos lógica al Kanban

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aquí es donde registras las rutas web para tu aplicación BuffeFast.
| Estas rutas son cargadas por el RouteServiceProvider dentro de un grupo
| que contiene el middleware "web".
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Cliente\ClienteAuthController;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cliente\MenuController;
use App\Http\Controllers\Cliente\CarritoController;


/*
|--------------------------------------------------------------------------
| RUTAS DEL CLIENTE
|--------------------------------------------------------------------------
*/

// Pantalla de login (Código + Nombre)
Route::get('/', function () {
    return view('cliente.ingreso');
})->name('cliente.inicio');
Route::post('/acceder', [ClienteAuthController::class, 'acceder'])->name('cliente.acceder');

// LA CARTA (Conectada a tu nuevo controlador)
Route::get('/carta', [MenuController::class, 'index'])->name('cliente.carta');

// --- RUTAS TEMPORALES (Para que no peten los botones de la vista) ---
Route::get('/carrito', [CarritoController::class, 'index'])->name('cliente.carrito');
Route::post('/carrito/add/{id}', function ($id) {
    return "Añadiste el plato $id al carrito";
})->name('cliente.carrito.add');
Route::get('/cuenta', function () {
    return "Página de cuenta en construcción 🚧";
})->name('cliente.cuenta');


// Cambia la ruta temporal por esta real:
Route::post('/carrito/add/{id}', [CarritoController::class, 'agregar'])->name('cliente.carrito.add');
Route::post('/carrito/remove/{id}', [CarritoController::class, 'eliminar'])->name('cliente.carrito.remove');
Route::get('/logout-cliente', [ClienteAuthController::class, 'logout'])->name('cliente.logout');

// ==========================================
// 👨‍🍳 MÓDULO STAFF (Administración y Cocina)
// ==========================================

// Rutas accesibles solo si NO estás logueado
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Rutas protegidas (Solo Staff logueado)
Route::middleware('auth')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Panel de Cocina (Kanban) - Por ahora devolvemos la vista directamente
    Route::get('/cocina', function () {
        return view('cocina.index');
    })->name('cocina.index');

    // Dashboard General (Para el Administrador)
    Route::get('/dashboard', function () {
        return "Bienvenido al Dashboard del Administrador (Próximamente)";
    })->name('dashboard');

});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas de Cocina
    Route::get('/cocina', [CocinaController::class, 'index'])->name('cocina.index');
    Route::patch('/cocina/pedido/{pedido}/estado', [CocinaController::class, 'cambiarEstado'])->name('cocina.pedido.estado');

    Route::get('/dashboard', function () {
        return "Bienvenido al Dashboard";
    })->name('dashboard');
});




use App\Http\Controllers\Admin\AdminController;

Route::middleware('auth')->group(function () {
    // ... tus otras rutas ...

    // Rutas de Admin/TPV
    Route::get('/dashboard', [AdminController::class, 'indexMesas'])->name('admin.mesas');
    Route::post('/mesa/{mesa}/activar', [AdminController::class, 'activarMesa'])->name('admin.mesa.activar');
});