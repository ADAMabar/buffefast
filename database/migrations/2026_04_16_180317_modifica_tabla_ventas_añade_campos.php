<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {

            $table->unsignedTinyInteger('num_comensales')->default(1)->after('sesion_id');

            $table->decimal('descuento', 10, 2)->default(0.00)->after('subtotal');

            $table->unsignedInteger('numero_mesa')->nullable()->after('sesion_id');

            DB::statement("ALTER TABLE ventas MODIFY COLUMN metodo_pago 
                ENUM('efectivo','tarjeta','transferencia','bizum','otro') 
                NOT NULL DEFAULT 'efectivo'");

            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('usuarios')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['num_comensales', 'descuento', 'numero_mesa']);
            DB::statement("ALTER TABLE ventas MODIFY COLUMN metodo_pago 
                ENUM('efectivo','tarjeta','transferencia') 
                NOT NULL DEFAULT 'efectivo'");
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};