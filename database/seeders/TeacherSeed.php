<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'admin',
            'last_name'  => 'admin',
            'email'  => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'role' => 'Teacher',
        ]);
    }
}
