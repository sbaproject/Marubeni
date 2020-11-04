<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * applications
         */

        DB::table('applications')->insert([
            'form_id' => 1,
            'group_id' => 1,
            'current_step' => 1,
            'status' => 0,
            'created_by' => 2,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('applications')->insert([
            'form_id' => 1,
            'group_id' => 1,
            'current_step' => 1,
            'status' => -1,
            'created_by' => 2,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('applications')->insert([
            'form_id' => 1,
            'group_id' => 1,
            'current_step' => 1,
            'status' => -2,
            'created_by' => 2,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('applications')->insert([
            'form_id' => 1,
            'group_id' => 1,
            'current_step' => 1,
            'status' => 99,
            'created_by' => 2,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('applications')->insert([
            'form_id' => 1,
            'group_id' => 1,
            'current_step' => 1,
            'status' => 3,
            'created_by' => 2,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('applications')->insert([
            'form_id' => 1,
            'group_id' => 1,
            'current_step' => 1,
            'status' => 2,
            'created_by' => 2,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('applications')->insert([
            'form_id' => 1,
            'group_id' => 1,
            'current_step' => 1,
            'status' => 5,
            'created_by' => 2,
            'updated_by' => 1,
            'created_at' => '2020-11-05 08:04:50',
            'updated_at' => Carbon::now(),
        ]);


    }
}
