<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor')->nullable();
            $table->string('seccion')->nullable();
            $table->string('tipo')->default('string');
            $table->string('descripcion')->nullable();
            $table->json('opciones')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};