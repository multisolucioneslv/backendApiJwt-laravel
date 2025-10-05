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
        Schema::create('cotizacion_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade'); // Cotización
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade'); // Producto
            $table->integer('cantidad')->default(1); // Cantidad
            $table->decimal('precio_unitario', 10, 2); // Precio unitario
            $table->decimal('descuento', 10, 2)->default(0); // Descuento por item
            $table->decimal('subtotal', 10, 2); // Subtotal del item
            $table->text('descripcion')->nullable(); // Descripción adicional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacion_items');
    }
};
