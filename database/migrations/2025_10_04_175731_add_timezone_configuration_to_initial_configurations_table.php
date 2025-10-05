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
        Schema::table('initial_configurations', function (Blueprint $table) {
            // Agregar configuración de zona horaria si no existe
            if (!Schema::hasColumn('initial_configurations', 'timezone_configuration')) {
                // No necesitamos agregar columnas, usaremos la tabla existente
                // Solo agregamos la configuración por defecto
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('initial_configurations', function (Blueprint $table) {
            // No hay columnas que eliminar
        });
    }
};
