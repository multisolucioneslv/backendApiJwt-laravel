<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías
        $categories = [
            [
                'name' => 'Productos',
                'description' => 'Productos de la empresa',
                'active' => true,
            ],
            [
                'name' => 'Servicios',
                'description' => 'Servicios de la empresa',
                'active' => true,
            ],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
