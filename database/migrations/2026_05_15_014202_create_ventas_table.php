<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero_ticket', 30)->nullable();
            $table->unsignedBigInteger('sesion_id')->nullable()->index('ventas_sesion_id_foreign');
            $table->unsignedInteger('numero_mesa')->nullable();
            $table->unsignedTinyInteger('num_comensales')->default(1);
            $table->decimal('subtotal', 10);
            $table->decimal('descuento', 10)->default(0);
            $table->decimal('iva', 10)->default(0);
            $table->decimal('total', 10);
            $table->decimal('propina', 10)->nullable();
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'bizum', 'otro'])->default('efectivo');
            $table->unsignedBigInteger('user_id')->nullable()->index('ventas_user_id_foreign');
            $table->text('observaciones')->nullable();
            $table->boolean('anulada')->default(false);
            $table->text('motivo_anulacion')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('caja_id')->nullable()->index('ventas_caja_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
