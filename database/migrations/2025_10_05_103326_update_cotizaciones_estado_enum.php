<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Paso 1: Agregar los nuevos valores al enum (manteniendo los antiguos)
        DB::statement("ALTER TABLE cotizaciones MODIFY COLUMN estado ENUM('borrador', 'enviada', 'aprobada', 'rechazada', 'vencida', 'generada', 'entregada', 'vendida') DEFAULT 'borrador'");
        
        // Paso 2: Actualizar los datos existentes para mapear a los nuevos estados
        DB::table('cotizaciones')->where('estado', 'borrador')->update(['estado' => 'generada']);
        DB::table('cotizaciones')->where('estado', 'enviada')->update(['estado' => 'entregada']);
        DB::table('cotizaciones')->where('estado', 'aprobada')->update(['estado' => 'vendida']);
        // 'rechazada' se mantiene igual
        // 'vencida' se puede mapear a 'rechazada' o mantener como está
        
        // Paso 3: Eliminar los valores antiguos del enum
        DB::statement("ALTER TABLE cotizaciones MODIFY COLUMN estado ENUM('generada', 'entregada', 'vendida', 'rechazada') DEFAULT 'generada'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Paso 1: Agregar los valores antiguos al enum
        DB::statement("ALTER TABLE cotizaciones MODIFY COLUMN estado ENUM('generada', 'entregada', 'vendida', 'rechazada', 'borrador', 'enviada', 'aprobada', 'vencida') DEFAULT 'generada'");
        
        // Paso 2: Revertir los cambios de datos
        DB::table('cotizaciones')->where('estado', 'generada')->update(['estado' => 'borrador']);
        DB::table('cotizaciones')->where('estado', 'entregada')->update(['estado' => 'enviada']);
        DB::table('cotizaciones')->where('estado', 'vendida')->update(['estado' => 'aprobada']);
        
        // Paso 3: Restaurar la columna estado original
        DB::statement("ALTER TABLE cotizaciones MODIFY COLUMN estado ENUM('borrador', 'enviada', 'aprobada', 'rechazada', 'vencida') DEFAULT 'borrador'");
    }
};