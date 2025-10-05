<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemType;

class SystemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systemTypes = [
            [
                'name' => 'Sistema por Defecto',
                'slug' => 'default',
                'description' => 'Sistema base con funcionalidades generales',
                'features' => [
                    'Gestión de usuarios',
                    'Configuraciones básicas',
                    'Dashboard general',
                    'Perfil de usuario'
                ],
                'required_tables' => [
                    'users',
                    'personal_access_tokens'
                ],
                'is_active' => true,
                'is_default' => true,
                'icon' => 'LayoutDashboard',
                'color' => '#3B82F6',
                'sort_order' => 1
            ],
            [
                'name' => 'Clínica Dental',
                'slug' => 'clinica-dental',
                'description' => 'Sistema especializado para clínicas dentales',
                'features' => [
                    'Gestión de pacientes',
                    'Historial dental',
                    'Recetas médicas',
                    'Servicios dentales',
                    'Control de ingresos y gastos',
                    'Tipos de servicios (limpieza, extracciones, endodoncias)'
                ],
                'required_tables' => [
                    'pacientes',
                    'historial_pacientes',
                    'historial_recetas',
                    'ingresos_consultas',
                    'gastos_implementos',
                    'tipos_servicios',
                    'tipos_extracciones',
                    'servicios_dentales'
                ],
                'is_active' => true,
                'is_default' => false,
                'icon' => 'Stethoscope',
                'color' => '#10B981',
                'sort_order' => 2
            ],
            [
                'name' => 'Clínica Médica',
                'slug' => 'clinica-medica',
                'description' => 'Sistema para clínica médica con un solo médico',
                'features' => [
                    'Gestión de pacientes',
                    'Historial médico',
                    'Recetas médicas',
                    'Control de ingresos y gastos',
                    'Servicios médicos'
                ],
                'required_tables' => [
                    'pacientes',
                    'historial_pacientes',
                    'historial_recetas',
                    'ingresos_consultas',
                    'gastos_implementos',
                    'servicios_medicos'
                ],
                'is_active' => true,
                'is_default' => false,
                'icon' => 'Heart',
                'color' => '#EF4444',
                'sort_order' => 3
            ],
            [
                'name' => 'Hospital',
                'slug' => 'hospital',
                'description' => 'Sistema completo para hospitales con múltiples médicos',
                'features' => [
                    'Gestión de médicos y enfermeras',
                    'Gestión de pacientes',
                    'Historial médico completo',
                    'Control de inventario',
                    'Gestión de áreas hospitalarias',
                    'Control de ingresos y gastos',
                    'Gestión de personal'
                ],
                'required_tables' => [
                    'medicos',
                    'enfermeras',
                    'areas_hospital',
                    'inventario',
                    'pacientes',
                    'historial_pacientes',
                    'historial_recetas',
                    'ingresos_consultas',
                    'gastos_implementos',
                    'gastos_locales',
                    'gastos_personal'
                ],
                'is_active' => true,
                'is_default' => false,
                'icon' => 'Building2',
                'color' => '#8B5CF6',
                'sort_order' => 4
            ],
            [
                'name' => 'Servicio de Cotizaciones',
                'slug' => 'cotizaciones',
                'description' => 'Sistema para empresas que manejan cotizaciones',
                'features' => [
                    'Gestión de clientes',
                    'Gestión de productos',
                    'Gestión de proveedores',
                    'Gestión de marcas',
                    'Creación de cotizaciones',
                    'Roles de empresa'
                ],
                'required_tables' => [
                    'clientes',
                    'productos',
                    'proveedores',
                    'marcas',
                    'cotizaciones',
                    'roles_empresa'
                ],
                'is_active' => true,
                'is_default' => false,
                'icon' => 'FileText',
                'color' => '#F59E0B',
                'sort_order' => 5
            ],
            [
                'name' => 'Servicio de Ventas',
                'slug' => 'ventas',
                'description' => 'Sistema completo de ventas y facturación',
                'features' => [
                    'Gestión de clientes',
                    'Gestión de productos',
                    'Gestión de proveedores',
                    'Gestión de marcas',
                    'Gestión de ventas',
                    'Facturación',
                    'Gestión de garantías',
                    'Múltiples monedas',
                    'Roles de empresa'
                ],
                'required_tables' => [
                    'clientes',
                    'productos',
                    'proveedores',
                    'marcas',
                    'cotizaciones',
                    'ventas',
                    'facturacion',
                    'garantias',
                    'monedas',
                    'roles_empresa'
                ],
                'is_active' => true,
                'is_default' => false,
                'icon' => 'ShoppingCart',
                'color' => '#06B6D4',
                'sort_order' => 6
            ],
            [
                'name' => 'Firma de Abogados',
                'slug' => 'firma-abogados',
                'description' => 'Sistema especializado para firmas de abogados',
                'features' => [
                    'Gestión de abogados',
                    'Gestión de clientes',
                    'Roles de abogado',
                    'Historial de clientes',
                    'Gestión de casos',
                    'Servicios legales',
                    'Plantillas de documentos',
                    'Documentación legal'
                ],
                'required_tables' => [
                    'abogados',
                    'clientes',
                    'roles_abogado',
                    'historial_cliente',
                    'trabajos_realizados',
                    'trabajos_pendientes',
                    'trabajos_terminados',
                    'servicios_legales',
                    'plantillas_documentos',
                    'casos',
                    'documentacion_legal'
                ],
                'is_active' => true,
                'is_default' => false,
                'icon' => 'Scale',
                'color' => '#6366F1',
                'sort_order' => 7
            ]
        ];

        foreach ($systemTypes as $systemType) {
            SystemType::create($systemType);
        }
    }
}
