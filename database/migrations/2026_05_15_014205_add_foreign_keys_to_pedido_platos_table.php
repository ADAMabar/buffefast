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
        Schema::table('pedido_platos', function (Blueprint $table) {
            $table->foreign(['pedido_id'], 'fk_pedido_platos_pedido')->references(['id'])->on('pedidos')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['plato_id'], 'fk_pedido_platos_plato')->references(['id'])->on('platos')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_platos', function (Blueprint $table) {
            $table->dropForeign('fk_pedido_platos_pedido');
            $table->dropForeign('fk_pedido_platos_plato');
        });
    }
};
