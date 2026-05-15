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
        Schema::create('sesiones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mesa_id')->nullable()->index('fk_sesiones_mesa');
            $table->string('codigo', 6)->nullable()->unique('codigo');
            $table->enum('estado', ['activa', 'solicitando_cuenta', 'cerrada'])->default('activa');
            $table->decimal('total_cobrado')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};
