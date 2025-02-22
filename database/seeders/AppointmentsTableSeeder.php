<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('appointments')->insert([
            [
                'запись_id' => 1,
                'клиент_id' => 1,
                'мастер_id' => 1,
                'услуга_id' => 1,
                'дата_время' => '2024-12-07 11:30:00',
                'статус' => 'Ожидает подтверждения',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'запись_id' => 2,
                'клиент_id' => 2,
                'мастер_id' => 2,
                'услуга_id' => 2,
                'дата_время' => '2024-12-09 15:40:00',
                'статус' => 'Ожидает подтверждения',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'запись_id' => 3,
                'клиент_id' => 3,
                'мастер_id' => 4,
                'услуга_id' => 3,
                'дата_время' => '2024-11-28 17:00:00',
                'статус' => 'Ожидает подтверждения',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
