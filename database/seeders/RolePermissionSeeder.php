<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos básicos del sistema
        $permissions = [
            // Dashboard
            ['name' => 'ver-dashboard', 'description' => 'Acceso al panel principal'],
            
            // Usuarios
            ['name' => 'ver-usuarios', 'description' => 'Visualizar lista de usuarios'],
            ['name' => 'crear-usuarios', 'description' => 'Crear nuevos usuarios'],
            ['name' => 'editar-usuarios', 'description' => 'Modificar usuarios existentes'],
            ['name' => 'eliminar-usuarios', 'description' => 'Eliminar usuarios'],
            
            // Productos
            ['name' => 'ver-productos', 'description' => 'Visualizar productos'],
            ['name' => 'crear-productos', 'description' => 'Agregar nuevos productos'],
            ['name' => 'editar-productos', 'description' => 'Modificar productos existentes'],
            ['name' => 'eliminar-productos', 'description' => 'Eliminar productos'],
            
            // Clientes
            ['name' => 'ver-clientes', 'description' => 'Visualizar clientes'],
            ['name' => 'crear-clientes', 'description' => 'Agregar nuevos clientes'],
            ['name' => 'editar-clientes', 'description' => 'Modificar clientes existentes'],
            ['name' => 'eliminar-clientes', 'description' => 'Eliminar clientes'],
            
            // Sucursales
            ['name' => 'ver-sucursales', 'description' => 'Visualizar sucursales'],
            ['name' => 'crear-sucursales', 'description' => 'Agregar nuevas sucursales'],
            ['name' => 'editar-sucursales', 'description' => 'Modificar sucursales existentes'],
            ['name' => 'eliminar-sucursales', 'description' => 'Eliminar sucursales'],
            
            // Categorías
            ['name' => 'ver-categorias', 'description' => 'Visualizar categorías'],
            ['name' => 'crear-categorias', 'description' => 'Agregar nuevas categorías'],
            ['name' => 'editar-categorias', 'description' => 'Modificar categorías existentes'],
            ['name' => 'eliminar-categorias', 'description' => 'Eliminar categorías'],
            
            // Sistema
            ['name' => 'configurar-sistema', 'description' => 'Modificar configuraciones del sistema'],
            ['name' => 'ver-reportes', 'description' => 'Acceso a reportes del sistema'],
            ['name' => 'gestionar-roles', 'description' => 'Crear y modificar roles de usuario'],
            
            // Paneles Admin
            ['name' => 'acceso-superadmin-panel', 'description' => 'Acceso al panel de SuperAdmin'],
            ['name' => 'acceso-admin-panel', 'description' => 'Acceso al panel de Admin'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']]);
        }

        // Crear rol SuperAdmin
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin']);

        // Crear rol Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Asignar todos los permisos al SuperAdmin
        $superAdminRole->syncPermissions(Permission::all());

        // Asignar permisos al Admin (todos excepto SuperAdmin panel y gestión de roles)
        $adminPermissions = Permission::whereNotIn('name', [
            'acceso-superadmin-panel', 
            'gestionar-roles'
        ])->get();
        $adminRole->syncPermissions($adminPermissions);

        // Buscar usuario jscothserver y asignarle el rol SuperAdmin
        $user = User::where('username', 'jscothserver')->first();
        if ($user) {
            $user->assignRole('SuperAdmin');
            $this->command->info('Usuario jscothserver asignado como SuperAdmin');
        } else {
            $this->command->warn('Usuario jscothserver no encontrado');
        }

        $this->command->info('Roles y permisos creados exitosamente');
    }
}
