<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * applicants
         */

        // Ha Noi

        DB::table('applicants')->insert([
            'location' => '0',
            'department_id' => 1,
            'role' => 1,
            'created_at' => Carbon::now(),
        ]);

        DB::table('applicants')->insert([
            'location' => '0',
            'department_id' => 1,
            'role' => 2,
            'created_at' => Carbon::now(),
        ]); 

        DB::table('applicants')->insert([
            'location' => '0',
            'department_id' => 4,
            'role' => 1,
            'created_at' => Carbon::now(),
        ]);

        DB::table('applicants')->insert([
            'location' => '0',
            'department_id' => 4,
            'role' => 2,
            'created_at' => Carbon::now(),
        ]);
        
        DB::table('applicants')->insert([
            'location' => '0',
            'department_id' => 5,
            'role' => 5,
            'created_at' => Carbon::now(),
        ]);
        

        // TP Ho CHi Minh

        DB::table('applicants')->insert([
            'location' => '1',
            'department_id' => 1,
            'role' => 1,
            'created_at' => Carbon::now(),
        ]);

        DB::table('applicants')->insert([
            'location' => '1',
            'department_id' => 1,
            'role' => 2,
            'created_at' => Carbon::now(),
        ]);

        DB::table('applicants')->insert([
            'location' => '1',
            'department_id' => 2,
            'role' => 1,
            'created_at' => Carbon::now(),
        ]);

        DB::table('applicants')->insert([
            'location' => '1',
            'department_id' => 2,
            'role' => 2,
            'created_at' => Carbon::now(),
        ]);

        DB::table('applicants')->insert([
            'location' => '1',
            'department_id' => 3,
            'role' => 1,
            'created_at' => Carbon::now(),
        ]);


        DB::table('applicants')->insert([
            'location' => '1',
            'department_id' => 3,
            'role' => 2,
            'created_at' => Carbon::now(),
        ]);

        DB::table('applicants')->insert([
            'location' => '1',
            'department_id' => 5,
            'role' => 4,
            'created_at' => Carbon::now(),
        ]);       
       
    }
}
