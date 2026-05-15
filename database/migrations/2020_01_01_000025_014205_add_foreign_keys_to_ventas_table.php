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
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign(['caja_id'])->references(['id'])->on('cajas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sesion_id'])->references(['id'])->on('sesiones')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('usuarios')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign('ventas_caja_id_foreign');
            $table->dropForeign('ventas_sesion_id_foreign');
            $table->dropForeign('ventas_user_id_foreign');
        });
    }
};
