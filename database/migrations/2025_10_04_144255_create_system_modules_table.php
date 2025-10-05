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
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_type_id')->constrained('system_types')->onDelete('cascade');
            $table->string('name'); // Nombre del módulo
            $table->string('slug'); // Slug del módulo
            $table->text('description')->nullable(); // Descripción del módulo
            $table->string('route')->nullable(); // Ruta del módulo
            $table->string('icon')->nullable(); // Icono del módulo
            $table->string('color')->nullable(); // Color del módulo
            $table->json('permissions')->nullable(); // Permisos del módulo en JSON
            $table->boolean('is_active')->default(true); // Si el módulo está activo
            $table->boolean('is_required')->default(false); // Si el módulo es requerido
            $table->integer('sort_order')->default(0); // Orden de visualización
            $table->timestamps();
            
            $table->unique(['system_type_id', 'slug']); // Slug único por sistema
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_modules');
    }
};
