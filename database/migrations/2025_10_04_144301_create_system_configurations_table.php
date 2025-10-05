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
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_type_id')->constrained('system_types')->onDelete('cascade');
            $table->string('key'); // Clave de configuración
            $table->text('value')->nullable(); // Valor de configuración
            $table->string('type')->default('string'); // Tipo de dato (string, boolean, integer, json)
            $table->text('description')->nullable(); // Descripción de la configuración
            $table->boolean('is_required')->default(false); // Si es requerida
            $table->boolean('is_public')->default(false); // Si es pública (visible en frontend)
            $table->timestamps();
            
            $table->unique(['system_type_id', 'key']); // Clave única por sistema
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configurations');
    }
};
