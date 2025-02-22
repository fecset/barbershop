<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('administrators')->insert([
            ['администратор_id' => 1, 'имя' => 'Иван Иванов', 'логин' => 'admin', 'пароль' => bcrypt('admin123')],
            ['администратор_id' => 2, 'имя' => 'Максим Максимов', 'логин' => 'admin2', 'пароль' => bcrypt('admin12345')]
        ]);
    }
}
