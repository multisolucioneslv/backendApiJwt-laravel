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
        Schema::create('system_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del tipo de sistema
            $table->string('slug')->unique(); // Slug único para identificar el sistema
            $table->text('description')->nullable(); // Descripción del sistema
            $table->json('features')->nullable(); // Características del sistema en JSON
            $table->json('required_tables')->nullable(); // Tablas requeridas en JSON
            $table->boolean('is_active')->default(true); // Si el sistema está activo
            $table->boolean('is_default')->default(false); // Si es el sistema por defecto
            $table->string('icon')->nullable(); // Icono del sistema
            $table->string('color')->nullable(); // Color del sistema
            $table->integer('sort_order')->default(0); // Orden de visualización
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_types');
    }
};
