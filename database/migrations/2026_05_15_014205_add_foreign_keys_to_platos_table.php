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
        Schema::table('platos', function (Blueprint $table) {
            $table->foreign(['categoria_id'], 'fk_platos_categoria')->references(['id'])->on('categorias')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platos', function (Blueprint $table) {
            $table->dropForeign('fk_platos_categoria');
        });
    }
};
