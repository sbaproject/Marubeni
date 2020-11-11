<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * departments
         */
        DB::table('departments')->insert(['name' => 'Sales']);  // 1
        DB::table('departments')->insert(['name' => 'ACCG']);   // 2
        DB::table('departments')->insert(['name' => 'ADMI']);   // 3
        DB::table('departments')->insert(['name' => 'HR/ADMI/ACCG']);   // 4
        DB::table('departments')->insert(['name' => 'DIRECTOR']);
    }
}
