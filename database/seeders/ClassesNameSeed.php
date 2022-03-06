<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesNameSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $AllClasses = ['Agriculture', 'Agricultural Economics', 'Agronomy', 'Animal Science', 'English and Literary Studies', 'Music', 'Theatre and Film Studies', 'Botany', 'Biochemistry', 'Marketing', 'Civil Engineering', 'Systems Engineering', 'Medicine'];

        foreach ($AllClasses as $classValue) {
            DB::table('classes')->insert(['name' => $classValue, 'created_at' => Carbon::now()]);
        }
    }
}
