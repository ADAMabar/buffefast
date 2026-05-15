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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sesion_id')->index('fk_pedidos_sesion');
            $table->unsignedBigInteger('cliente_id')->index('fk_pedidos_cliente');
            $table->integer('ronda');
            $table->enum('estado', ['pendiente', 'preparando', 'servido'])->default('pendiente');
            $table->boolean('visible_cocina')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
