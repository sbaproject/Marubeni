<?php

namespace Database\Seeders;

use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * budgets
         */

         DB::table('budgets')->truncate();

        // Biz Trip

        DB::table('budgets')->insert([
            'budget_type' => 2,
            'step_type' => 1,
            'position' => 3,
            'amount' => 0,
            'created_at' => Carbon::now(),
        ]);

        DB::table('budgets')->insert([
            'budget_type' => 2,
            'step_type' => 2,
            'position' => 3,
            'amount' => 0,
            'created_at' => Carbon::now(),
        ]);

        DB::table('budgets')->insert([
            'budget_type' => 2,
            'step_type' => 1,
            'position' => 4,
            'amount' => 0,
            'created_at' => Carbon::now(),
        ]);

        DB::table('budgets')->insert([
            'budget_type' => 2,
            'step_type' => 2,
            'position' => 4,
            'amount' => 0,
            'created_at' => Carbon::now(),
        ]);


        // Entertaiment

        DB::table('budgets')->insert([
            'budget_type' => 3,
            'step_type' => 1,
            'position' => 1,
            'amount' => 2000000,
            'created_at' => Carbon::now(),
        ]);

        DB::table('budgets')->insert([
            'budget_type' => 3,
            'step_type' => 2,
            'position' => 1,
            'amount' => 2000000,
            'created_at' => Carbon::now(),
        ]);

        DB::table('budgets')->insert([
            'budget_type' => 3,
            'step_type' => 1,
            'position' => 2,
            'amount' => 4000000,
            'created_at' => Carbon::now(),
        ]);

        DB::table('budgets')->insert([
            'budget_type' => 3,
            'step_type' => 2,
            'position' => 2,
            'amount' => 4000000,
            'created_at' => Carbon::now(),
        ]);
    }
}
