<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperadminsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('super_administrators')->insert([
            [
                'суперадминистратор_id' => 1,
                'имя' => 'Владимир',
                'логин' => 'superadmin',
                'пароль' => bcrypt('superadmin123'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
