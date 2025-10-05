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
        Schema::create('initial_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Clave única para cada configuración
            $table->text('value'); // Valor de la configuración (JSON o texto)
            $table->string('type')->default('string'); // Tipo de dato: string, boolean, json, integer
            $table->text('description')->nullable(); // Descripción de la configuración
            $table->boolean('is_required')->default(false); // Si es requerida para el funcionamiento
            $table->boolean('is_public')->default(false); // Si es visible para usuarios no admin
            $table->timestamps();
            
            // Índices
            $table->index(['key', 'is_required']);
            $table->index(['is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_configurations');
    }
};
