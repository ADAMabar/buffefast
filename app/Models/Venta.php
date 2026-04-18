<?php

// ============================================================
// 1. RUTAS — añade en routes/web.php dentro del grupo auth
// ============================================================

// Route::middleware(['auth'])->group(function () {
//
//     Route::get( '/admin/historial',                  [HistorialController::class, 'index'])  ->name('admin.historial');
//     Route::get( '/admin/ventas/{venta}',             [HistorialController::class, 'show'])   ->name('admin.ventas.show');
//     Route::patch('/admin/ventas/{venta}/anular',     [HistorialController::class, 'anular']) ->name('admin.ventas.anular');
//
// });
//
// No necesitas ruta separada para exportar: se llama desde index() con ?export=csv


// ============================================================
// 2. CAMPOS QUE NECESITA TU MODELO Venta (app/Models/Venta.php)
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'sesion_id',
        'numero_mesa',       // int  — desnormalizado al cerrar mesa
        'num_comensales',    // int  — desnormalizado al cerrar mesa
        'subtotal',          // decimal — base antes de descuento e IVA
        'descuento',         // decimal — euros de descuento (0 si no hay)
        'iva',               // decimal — importe del IVA
        'total',             // decimal — subtotal - descuento + iva
        'propina',           // decimal — propina voluntaria (nullable)
        'metodo_pago',       // enum: efectivo | tarjeta | bizum | transferencia
        'user_id',           // FK a usuarios (quien cobró)
        'caja_id',           // FK a cajas
        'numero_ticket',     // string — si quieres numeración propia (nullable)
        'observaciones',     // text — nullable
        'anulada',           // boolean — false por defecto
        'motivo_anulacion',  // text — nullable, se rellena al anular
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'propina' => 'decimal:2',
        'num_comensales' => 'integer',
        'anulada' => 'boolean',
    ];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class);
    }
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
}


// ============================================================
// 3. MIGRACIÓN para añadir los campos que faltan en ventas
// ============================================================
// php artisan make:migration add_campos_historial_to_ventas_table

// Schema::table('ventas', function (Blueprint $table) {
//
//     // Si no los tienes todavía:
//     $table->unsignedInteger('numero_mesa')->nullable()->after('sesion_id');
//     $table->unsignedTinyInteger('num_comensales')->default(1)->after('numero_mesa');
//     $table->decimal('descuento', 10, 2)->default(0)->after('subtotal');
//     $table->decimal('propina',   10, 2)->nullable()->after('total');
//     $table->string('numero_ticket', 30)->nullable()->after('id');
//     $table->boolean('anulada')->default(false)->after('observaciones');
//     $table->text('motivo_anulacion')->nullable()->after('anulada');
//
//     // Corregir el enum de metodo_pago para incluir bizum:
//     DB::statement("ALTER TABLE ventas MODIFY COLUMN metodo_pago
//         ENUM('efectivo','tarjeta','transferencia','bizum','otro')
//         NOT NULL DEFAULT 'efectivo'");
//
//     // Corregir FK user_id → apunta a 'usuarios' no a 'users'
//     $table->dropForeign(['user_id']);
//     $table->foreign('user_id')->references('id')->on('usuarios')->onDelete('set null');
// });


// ============================================================
// 4. CÓMO CREAR LA VENTA al cerrar mesa (MesaController@desocupar)
// ============================================================

// DB::transaction(function () use ($mesa, $sesion, $request) {
//
//     $comensales  = $sesion->clientes()->count();
//     $precio      = (float) Configuracion::where('clave', 'precio_buffet_adulto')->value('valor') ?? 0;
//     $ivaPct      = (float) Configuracion::where('clave', 'porcentaje_impuestos')->value('valor') ?? 10;
//
//     $subtotal  = $comensales * $precio;
//     $descuento = (float) ($request->descuento ?? 0);
//     $base      = $subtotal - $descuento;
//     $iva       = round($base * ($ivaPct / 100), 2);
//     $total     = round($base + $iva, 2);
//
//     Venta::create([
//         'sesion_id'      => $sesion->id,
//         'numero_mesa'    => $mesa->numero,
//         'num_comensales' => $comensales,
//         'subtotal'       => $subtotal,
//         'descuento'      => $descuento,
//         'iva'            => $iva,
//         'propina'        => (float) ($request->propina ?? 0),
//         'total'          => $total,
//         'metodo_pago'    => $request->metodo_pago ?? 'efectivo',
//         'user_id'        => auth()->id(),
//         'caja_id'        => $request->caja_id,
//         'observaciones'  => $request->observaciones,
//         'anulada'        => false,
//     ]);
//
//     $sesion->update(['estado' => 'cerrada', 'total_cobrado' => $total]);
// });