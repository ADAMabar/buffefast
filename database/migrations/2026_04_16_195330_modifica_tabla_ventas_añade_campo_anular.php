<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Comprobamos una por una para que no explote si alguna ya existe
            if (!Schema::hasColumn('ventas', 'numero_mesa')) {
                $table->integer('numero_mesa')->nullable()->after('sesion_id');
            }
            if (!Schema::hasColumn('ventas', 'num_comensales')) {
                $table->unsignedTinyInteger('num_comensales')->default(1)->after('numero_mesa');
            }
            if (!Schema::hasColumn('ventas', 'descuento')) {
                $table->decimal('descuento', 10, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('ventas', 'propina')) {
                $table->decimal('propina', 10, 2)->nullable()->after('total');
            }
            if (!Schema::hasColumn('ventas', 'numero_ticket')) {
                $table->string('numero_ticket', 30)->nullable()->after('id');
            }
            if (!Schema::hasColumn('ventas', 'anulada')) {
                $table->boolean('anulada')->default(false)->after('observaciones');
            }
            if (!Schema::hasColumn('ventas', 'motivo_anulacion')) {
                $table->text('motivo_anulacion')->nullable()->after('anulada');
            }
        });

        // Esto actualiza el enum para aceptar Bizum sin dar error
        DB::statement("ALTER TABLE ventas MODIFY COLUMN metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia', 'bizum', 'otro') NOT NULL DEFAULT 'efectivo'");
    }
};