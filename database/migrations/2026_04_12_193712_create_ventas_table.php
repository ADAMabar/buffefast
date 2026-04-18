<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            // 1. Relación con la sesión (De aquí sacamos qué mesa era y qué clientes había)
            // Usamos 'set null' para que si borramos una sesión antigua, el registro de la venta NO desaparezca
            $table->foreignId('sesion_id')
                ->nullable()
                ->constrained('sesiones')
                ->onDelete('set null');

            // 2. Bloque de Dinero (Usamos decimal para precisión exacta)
            // 10 dígitos en total, 2 decimales (hasta 99.999.999,99)
            $table->decimal('subtotal', 10, 2);
            $table->decimal('iva', 10, 2)->default(0.00); // Por si en el futuro necesitas desglosar impuestos
            $table->decimal('total', 10, 2);

            // 3. Información del Pago
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');

            // 4. Auditoría (Quién cobró, para evitar robos o errores)
            // Si usas el sistema de usuarios de Laravel (Breeze/Jetstream), esto es clave
            $table->foreignId('user_id')->nullable()->constrained('users');

            // 5. Notas extra
            $table->text('observaciones')->nullable();

            $table->timestamps(); // created_at será la "Fecha de Factura"
            $table->foreignId('caja_id')->constrained('cajas');

        });

    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};