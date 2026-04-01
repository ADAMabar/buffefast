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
// ==========================================
// 📱 MÓDULO CLIENTE (Mobile-First)
// ==========================================

// Pantalla de ingreso del código de mesa
Route::get('/', [ClienteAuthController::class, 'mostrarIngreso'])->name('cliente.login');
Route::post('/ingresar', [ClienteAuthController::class, 'ingresar'])->name('cliente.ingresar');
Route::get('/carta', [ClienteController::class, 'carta'])->name('cliente.carta');
Route::post('/carta', [ClienteController::class, 'agregarAlCarrito'])->name('cliente.agregarAlCarrito');

// La carta (Menú) - La crearemos en el siguiente paso
/*Route::get('/carta', function () {
    return "¡Bienvenido a la mesa! Aquí irá la carta de Sushi.";
})->name('cliente.carta');*/

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




Route::get('/carta', [ClienteController::class, 'carta'])->name('cliente.carta');
use App\Http\Controllers\Admin\AdminController;

Route::middleware('auth')->group(function () {
    // ... tus otras rutas ...

    // Rutas de Admin/TPV
    Route::get('/dashboard', [AdminController::class, 'indexMesas'])->name('admin.mesas');
    Route::post('/mesa/{mesa}/activar', [AdminController::class, 'activarMesa'])->name('admin.mesa.activar');
});