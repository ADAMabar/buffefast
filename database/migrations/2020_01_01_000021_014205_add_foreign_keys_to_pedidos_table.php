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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreign(['cliente_id'], 'fk_pedidos_cliente')->references(['id'])->on('clientes')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['sesion_id'], 'fk_pedidos_sesion')->references(['id'])->on('sesiones')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('fk_pedidos_cliente');
            $table->dropForeign('fk_pedidos_sesion');
        });
    }
};
