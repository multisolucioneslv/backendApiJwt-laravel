<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Telegram;
class TelegramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $telegrams = [
            [
                'telegramID' => '1234567890',
            ],
            [
                'telegramID' => '1234567890',
            ],
            [
                'telegramID' => '1234567890',
            ],
        ];
        foreach ($telegrams as $telegram) {
            Telegram::create($telegram);
        }
    }
}
