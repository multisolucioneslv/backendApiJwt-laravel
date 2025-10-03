<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Sucursale;
use App\Models\Telegram;
use App\Models\Phone;
use App\Models\Sex; 
use App\Models\Category;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SexSeeder::class,
            PhoneSeeder::class,
            TelegramSeeder::class,
        ]);

        User::factory()->create([
            'username'  => 'jscothserver',
            'name'      => 'Juan C.',
            'lastname'  => 'Torres',
            'telegram_id'   => 1,
            'phone_id'  => 1,
            'status'    => 'activo',
            'email'     => 'jscothserver@gmail.com',
            'password'  => Hash::make('72900968'),
            'sex_id'    => 1,
        ]);
        $this->call([
            SucursaleSeeder::class,
            CategorySeeder::class,
            ProductoSeeder::class,
            CustomerSeeder::class,
        ]);
    }
}
