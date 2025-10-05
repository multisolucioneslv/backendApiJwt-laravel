<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InitialConfiguration;

class TimezoneConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configurar zona horaria por defecto
        InitialConfiguration::setByKey(
            'system_timezone',
            'America/Los_Angeles',
            'string',
            'Zona horaria configurada para el sistema'
        );

        $this->command->info('Zona horaria configurada como: America/Los_Angeles');
    }
}