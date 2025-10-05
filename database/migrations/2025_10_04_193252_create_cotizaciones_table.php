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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cotizacion')->unique(); // Número de cotización único (ej: COT-2025-0001)
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // Cliente
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que crea la cotización
            $table->date('fecha_cotizacion'); // Fecha de emisión
            $table->date('fecha_vencimiento'); // Fecha de vencimiento
            $table->enum('estado', ['borrador', 'enviada', 'aprobada', 'rechazada', 'vencida'])->default('borrador'); // Estado
            $table->decimal('subtotal', 10, 2)->default(0); // Subtotal
            $table->decimal('impuesto', 10, 2)->default(0); // Impuestos
            $table->decimal('descuento', 10, 2)->default(0); // Descuento
            $table->decimal('total', 10, 2)->default(0); // Total
            $table->text('notas')->nullable(); // Notas adicionales
            $table->text('terminos')->nullable(); // Términos y condiciones
            $table->timestamps();
            $table->softDeletes(); // Eliminación lógica
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
