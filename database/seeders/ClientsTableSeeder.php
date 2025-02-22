<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('clients')->insert([
            [
                'клиент_id' => 1,
                'имя' => 'Алексей',
                'телефон' => '+79991112233',
                'email' => 'alex@mail.ru',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'клиент_id' => 2,
                'имя' => 'Борис',
                'телефон' => '+79997544455',
                'email' => 'boris@mail.ru',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'клиент_id' => 3,
                'имя' => 'Максим',
                'телефон' => '+79995745677',
                'email' => 'maxim@mail.ru',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
