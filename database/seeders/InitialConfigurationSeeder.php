<?php

namespace Database\Seeders;

use App\Models\InitialConfiguration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configuraciones iniciales del sistema
        $configurations = [
            [
                'key' => 'initial_setup_completed',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Indica si la configuración inicial del sistema está completada',
                'is_required' => true,
                'is_public' => true
            ],
            [
                'key' => 'selected_system_type',
                'value' => 'null',
                'type' => 'json',
                'description' => 'Tipo de sistema seleccionado durante la configuración inicial',
                'is_required' => false,
                'is_public' => true
            ],
            [
                'key' => 'admin_data',
                'value' => 'null',
                'type' => 'json',
                'description' => 'Datos del administrador creado durante la configuración inicial',
                'is_required' => false,
                'is_public' => false
            ],
            [
                'key' => 'selected_modules',
                'value' => '[]',
                'type' => 'json',
                'description' => 'Módulos seleccionados durante la configuración inicial',
                'is_required' => false,
                'is_public' => true
            ],
            [
                'key' => 'system_name',
                'value' => 'Sistema Multi-Servicio',
                'type' => 'string',
                'description' => 'Nombre del sistema configurado',
                'is_required' => false,
                'is_public' => true
            ],
            [
                'key' => 'system_version',
                'value' => '1.0.0',
                'type' => 'string',
                'description' => 'Versión del sistema',
                'is_required' => false,
                'is_public' => true
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Modo de mantenimiento del sistema',
                'is_required' => false,
                'is_public' => true
            ],
            [
                'key' => 'max_users',
                'value' => '100',
                'type' => 'integer',
                'description' => 'Número máximo de usuarios permitidos',
                'is_required' => false,
                'is_public' => false
            ],
            [
                'key' => 'session_timeout',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Tiempo de expiración de sesión en minutos',
                'is_required' => false,
                'is_public' => false
            ],
            [
                'key' => 'backup_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Habilitar respaldos automáticos',
                'is_required' => false,
                'is_public' => false
            ]
        ];

        foreach ($configurations as $config) {
            InitialConfiguration::create($config);
        }
    }
}
