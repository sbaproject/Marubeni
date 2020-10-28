<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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

        // /**
        //  * Budgets
        //  */
        // DB::table('budgets')->insert([
        //     'budget_type' => 0,
        //     'cost_type' => 0,
        //     'position' => 2,
        //     'amount' => 4000000
        // ]);
        // DB::table('budgets')->insert([
        //     'budget_type' => 0,
        //     'cost_type' => 0,
        //     'position' => 3,
        //     'amount' => 10000000
        // ]);
        // DB::table('budgets')->insert([
        //     'budget_type' => 0,
        //     'cost_type' => 1,
        //     'position' => 2,
        //     'amount' => 5000000
        // ]);
        // DB::table('budgets')->insert([
        //     'budget_type' => 0,
        //     'cost_type' => 1,
        //     'position' => 3,
        //     'amount' => 15000000
        // ]);

        // DB::table('budgets')->insert([
        //     'budget_type' => 1,
        //     'cost_type' => 0,
        //     'position' => 0,
        //     'amount' => 4000000
        // ]);
        // DB::table('budgets')->insert([
        //     'budget_type' => 1,
        //     'cost_type' => 0,
        //     'position' => 1,
        //     'amount' => 10000000
        // ]);
        // DB::table('budgets')->insert([
        //     'budget_type' => 1,
        //     'cost_type' => 1,
        //     'position' => 0,
        //     'amount' => 5000000
        // ]);
        // DB::table('budgets')->insert([
        //     'budget_type' => 1,
        //     'cost_type' => 1,
        //     'position' => 1,
        //     'amount' => 15000000
        // ]);

        // /**
        //  * companies
        //  */
        // DB::table('companies')->insert([
        //     'company_name' => 'SBA',
        //     'attendants_name' => 'Tí, Sửu, Dần',
        //     'email' => 'sba@gmail.com'
        // ]);
        // DB::table('companies')->insert([
        //     'company_name' => 'Nokia',
        //     'attendants_name' => 'Micheal',
        //     'email' => 'micheal@gmail.com'
        // ]);

        // /**
        //  * applications
        //  */
        // DB::Table('applications')->insert([
        //     'name' => 'Entertaiment'
        // ]);
        // DB::Table('applications')->insert([
        //     'name' => 'Business Trip'
        // ]);
        // DB::Table('applications')->insert([
        //     'name' => 'Leave'
        // ]);
    }
}
