<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sucursale;
class SucursaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sucursales = [
            [
                'nombre' => 'Casa Matriz',
                'direccion' => 'Direccion 1',
                'activo' => true,
                'principal' => true,
                'user_id' => 1,
            ],
            [
                'nombre' => 'Sucursal 2',
                'direccion' => 'Direccion 2',
                'activo' => true,
                'principal' => false,
                'user_id' => 1,
            ],
            [
                'nombre' => 'Sucursal 3',
                'direccion' => 'Direccion 3',
                'activo' => true,
                'principal' => false,
                'user_id' => 1,
            ],
        ];
        foreach ($sucursales as $sucursal) {
            Sucursale::create($sucursal);
        }
        
    }
}
