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
        Schema::create('user_system_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('system_type_id')->constrained('system_types')->onDelete('cascade');
            $table->json('module_permissions')->nullable(); // Permisos específicos por módulo
            $table->boolean('is_admin')->default(false); // Si es administrador del sistema
            $table->boolean('is_active')->default(true); // Si el acceso está activo
            $table->timestamp('granted_at')->nullable(); // Fecha de concesión
            $table->timestamp('expires_at')->nullable(); // Fecha de expiración
            $table->timestamps();
            
            $table->unique(['user_id', 'system_type_id']); // Un usuario por sistema
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_system_access');
    }
};
