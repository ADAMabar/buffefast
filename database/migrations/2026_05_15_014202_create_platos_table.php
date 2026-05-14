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
        Schema::create('platos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->decimal('precio')->default(0);
            $table->string('imagen')->nullable();
            $table->unsignedBigInteger('categoria_id')->index('fk_platos_categoria');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platos');
    }
};
