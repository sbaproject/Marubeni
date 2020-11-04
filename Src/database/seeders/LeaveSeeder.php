<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * leaves
         */

        DB::table('leaves')->insert([
            'application_id' => 1,
            'code_leave' => 0,
            'paid_type' => 0,
            'reason_leave' => 'I do not care',
            'date_from' => Carbon::now()->toDateString(),
            'date_to' => Carbon::now()->toDateString(),
            'time_day' => Carbon::now()->toDateString(),
            'time_from' => Carbon::now()->toTimeString(),
            'time_to' => Carbon::now()->toTimeString(),
            'maternity_from' => Carbon::now()->toDateString(),
            'maternity_to' => Carbon::now()->toDateString(),
            'days_use' => 12,
            'times_use' => 6,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
