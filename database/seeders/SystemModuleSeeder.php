<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemType;
use App\Models\SystemModule;

class SystemModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener tipos de sistema
        $systemTypes = SystemType::all();
        
        // Definir módulos para cada tipo de sistema
        $modulesData = [
            'default' => [
                [
                    'name' => 'Dashboard',
                    'slug' => 'dashboard',
                    'description' => 'Panel principal del sistema',
                    'route' => '/app/dashboard',
                    'icon' => 'LayoutDashboard',
                    'color' => '#3b82f6',
                    'permissions' => ['view'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 1
                ],
                [
                    'name' => 'Usuarios',
                    'slug' => 'users',
                    'description' => 'Gestión de usuarios del sistema',
                    'route' => '/app/users',
                    'icon' => 'Users',
                    'color' => '#10b981',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Configuraciones',
                    'slug' => 'settings',
                    'description' => 'Configuración del sistema',
                    'route' => '/app/settings',
                    'icon' => 'Settings',
                    'color' => '#8b5cf6',
                    'permissions' => ['view', 'edit'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 3
                ]
            ],
            'dental-clinic' => [
                [
                    'name' => 'Dashboard',
                    'slug' => 'dashboard',
                    'description' => 'Panel principal de la clínica dental',
                    'route' => '/app/dashboard',
                    'icon' => 'LayoutDashboard',
                    'color' => '#3b82f6',
                    'permissions' => ['view'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 1
                ],
                [
                    'name' => 'Pacientes',
                    'slug' => 'patients',
                    'description' => 'Gestión de pacientes dentales',
                    'route' => '/app/patients',
                    'icon' => 'Users',
                    'color' => '#10b981',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Citas',
                    'slug' => 'appointments',
                    'description' => 'Programación de citas dentales',
                    'route' => '/app/appointments',
                    'icon' => 'Calendar',
                    'color' => '#f59e0b',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 3
                ],
                [
                    'name' => 'Tratamientos',
                    'slug' => 'treatments',
                    'description' => 'Catálogo de tratamientos dentales',
                    'route' => '/app/treatments',
                    'icon' => 'Stethoscope',
                    'color' => '#ef4444',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 4
                ],
                [
                    'name' => 'Historial Médico',
                    'slug' => 'medical-history',
                    'description' => 'Historial dental de pacientes',
                    'route' => '/app/medical-history',
                    'icon' => 'FileText',
                    'color' => '#6b7280',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => false,
                    'sort_order' => 5
                ]
            ],
            'medical-clinic' => [
                [
                    'name' => 'Dashboard',
                    'slug' => 'dashboard',
                    'description' => 'Panel principal de la clínica médica',
                    'route' => '/app/dashboard',
                    'icon' => 'LayoutDashboard',
                    'color' => '#3b82f6',
                    'permissions' => ['view'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 1
                ],
                [
                    'name' => 'Pacientes',
                    'slug' => 'patients',
                    'description' => 'Gestión de pacientes médicos',
                    'route' => '/app/patients',
                    'icon' => 'Users',
                    'color' => '#10b981',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Citas',
                    'slug' => 'appointments',
                    'description' => 'Programación de citas médicas',
                    'route' => '/app/appointments',
                    'icon' => 'Calendar',
                    'color' => '#f59e0b',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 3
                ],
                [
                    'name' => 'Consultas',
                    'slug' => 'consultations',
                    'description' => 'Registro de consultas médicas',
                    'route' => '/app/consultations',
                    'icon' => 'Heart',
                    'color' => '#ef4444',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 4
                ],
                [
                    'name' => 'Recetas',
                    'slug' => 'prescriptions',
                    'description' => 'Gestión de recetas médicas',
                    'route' => '/app/prescriptions',
                    'icon' => 'FileText',
                    'color' => '#8b5cf6',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => false,
                    'sort_order' => 5
                ]
            ],
            'hospital' => [
                [
                    'name' => 'Dashboard',
                    'slug' => 'dashboard',
                    'description' => 'Panel principal del hospital',
                    'route' => '/app/dashboard',
                    'icon' => 'LayoutDashboard',
                    'color' => '#3b82f6',
                    'permissions' => ['view'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 1
                ],
                [
                    'name' => 'Pacientes',
                    'slug' => 'patients',
                    'description' => 'Gestión de pacientes hospitalarios',
                    'route' => '/app/patients',
                    'icon' => 'Users',
                    'color' => '#10b981',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Admisiones',
                    'slug' => 'admissions',
                    'description' => 'Gestión de admisiones hospitalarias',
                    'route' => '/app/admissions',
                    'icon' => 'Building2',
                    'color' => '#f59e0b',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 3
                ],
                [
                    'name' => 'Quirófanos',
                    'slug' => 'operating-rooms',
                    'description' => 'Gestión de quirófanos',
                    'route' => '/app/operating-rooms',
                    'icon' => 'Stethoscope',
                    'color' => '#ef4444',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 4
                ],
                [
                    'name' => 'Inventario',
                    'slug' => 'inventory',
                    'description' => 'Control de inventario médico',
                    'route' => '/app/inventory',
                    'color' => '#6b7280',
                    'icon' => 'Package',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => false,
                    'sort_order' => 5
                ]
            ],
            'quotations' => [
                [
                    'name' => 'Dashboard',
                    'slug' => 'dashboard',
                    'description' => 'Panel principal de cotizaciones',
                    'route' => '/app/dashboard',
                    'icon' => 'LayoutDashboard',
                    'color' => '#3b82f6',
                    'permissions' => ['view'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 1
                ],
                [
                    'name' => 'Cotizaciones',
                    'slug' => 'quotations',
                    'description' => 'Gestión de cotizaciones',
                    'route' => '/app/quotations',
                    'icon' => 'FileText',
                    'color' => '#10b981',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Clientes',
                    'slug' => 'customers',
                    'description' => 'Gestión de clientes',
                    'route' => '/app/customers',
                    'icon' => 'Users',
                    'color' => '#f59e0b',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 3
                ],
                [
                    'name' => 'Productos',
                    'slug' => 'products',
                    'description' => 'Catálogo de productos',
                    'route' => '/app/products',
                    'icon' => 'Package',
                    'color' => '#8b5cf6',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 4
                ]
            ],
            'sales' => [
                [
                    'name' => 'Dashboard',
                    'slug' => 'dashboard',
                    'description' => 'Panel principal de ventas',
                    'route' => '/app/dashboard',
                    'icon' => 'LayoutDashboard',
                    'color' => '#3b82f6',
                    'permissions' => ['view'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 1
                ],
                [
                    'name' => 'Ventas',
                    'slug' => 'sales',
                    'description' => 'Gestión de ventas',
                    'route' => '/app/sales',
                    'icon' => 'ShoppingCart',
                    'color' => '#10b981',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Clientes',
                    'slug' => 'customers',
                    'description' => 'Gestión de clientes',
                    'route' => '/app/customers',
                    'icon' => 'Users',
                    'color' => '#f59e0b',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 3
                ],
                [
                    'name' => 'Productos',
                    'slug' => 'products',
                    'description' => 'Catálogo de productos',
                    'route' => '/app/products',
                    'icon' => 'Package',
                    'color' => '#8b5cf6',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 4
                ],
                [
                    'name' => 'Inventario',
                    'slug' => 'inventory',
                    'description' => 'Control de inventario',
                    'route' => '/app/inventory',
                    'icon' => 'Archive',
                    'color' => '#6b7280',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => false,
                    'sort_order' => 5
                ]
            ],
            'law-firm' => [
                [
                    'name' => 'Dashboard',
                    'slug' => 'dashboard',
                    'description' => 'Panel principal de la firma legal',
                    'route' => '/app/dashboard',
                    'icon' => 'LayoutDashboard',
                    'color' => '#3b82f6',
                    'permissions' => ['view'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 1
                ],
                [
                    'name' => 'Clientes',
                    'slug' => 'clients',
                    'description' => 'Gestión de clientes legales',
                    'route' => '/app/clients',
                    'icon' => 'Users',
                    'color' => '#10b981',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Casos',
                    'slug' => 'cases',
                    'description' => 'Gestión de casos legales',
                    'route' => '/app/cases',
                    'icon' => 'Scale',
                    'color' => '#f59e0b',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 3
                ],
                [
                    'name' => 'Documentos',
                    'slug' => 'documents',
                    'description' => 'Gestión de documentos legales',
                    'route' => '/app/documents',
                    'icon' => 'FileText',
                    'color' => '#8b5cf6',
                    'permissions' => ['view', 'create', 'edit'],
                    'is_active' => true,
                    'is_required' => true,
                    'sort_order' => 4
                ],
                [
                    'name' => 'Citas',
                    'slug' => 'appointments',
                    'description' => 'Programación de citas legales',
                    'route' => '/app/appointments',
                    'icon' => 'Calendar',
                    'color' => '#ef4444',
                    'permissions' => ['view', 'create', 'edit', 'delete'],
                    'is_active' => true,
                    'is_required' => false,
                    'sort_order' => 5
                ]
            ]
        ];

        // Crear módulos para cada tipo de sistema
        foreach ($systemTypes as $systemType) {
            $systemSlug = $systemType->slug;
            
            if (isset($modulesData[$systemSlug])) {
                foreach ($modulesData[$systemSlug] as $moduleData) {
                    SystemModule::create([
                        'system_type_id' => $systemType->id,
                        'name' => $moduleData['name'],
                        'slug' => $moduleData['slug'],
                        'description' => $moduleData['description'],
                        'route' => $moduleData['route'],
                        'icon' => $moduleData['icon'],
                        'color' => $moduleData['color'],
                        'permissions' => json_encode($moduleData['permissions']),
                        'is_active' => $moduleData['is_active'],
                        'is_required' => $moduleData['is_required'],
                        'sort_order' => $moduleData['sort_order']
                    ]);
                }
            }
        }
    }
}