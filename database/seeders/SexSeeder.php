<?php

namespace Database\Seeders;
use App\Models\Sex;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class SexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Masculino
        Sex::create([
            'name' => 'Masculino',
            'inicial' => 'M',
        ]);
        
        // Femenino
        Sex::create([
            'name' => 'Femenino',
            'inicial' => 'F',
        ]);
    }
}
