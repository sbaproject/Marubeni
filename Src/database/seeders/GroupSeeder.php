<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * groups
         */

        DB::table('groups')->insert([
            'budget_id' => 1,
            'budget_type_compare' => 1,
            'applicant_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('groups')->insert([
            'applicant_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
