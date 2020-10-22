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
        // DB::table('users')->truncate();
        // DB::table('roles')->truncate();
        // DB::table('departments')->truncate();
        // DB::table('budgets')->truncate();
        // DB::table('companies')->truncate();

        /**
         * roles
         */
        DB::table('roles')->insert(['name' => 'Staff']);        // 1
        DB::table('roles')->insert(['name' => 'GM']);           // 2
        DB::table('roles')->insert(['name' => 'PIC']);          // 3
        DB::table('roles')->insert(['name' => 'DGD']);          // 4
        DB::table('roles')->insert(['name' => 'GD']);           // 5
        DB::table('roles')->insert(['name' => 'Admin']);        // 6

        /**
         * departments
         */
        DB::table('departments')->insert(['name' => 'Sales']);  // 1
        DB::table('departments')->insert(['name' => 'ACCG']);   // 2
        DB::table('departments')->insert(['name' => 'ADMI']);   // 3

        /**
         * users
         */
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => 6, // admin
            'department_id' => 3, // ADMI
            'approval' => 0 // OFF
        ]);
        DB::table('users')->insert([
            'name' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => 1, // Staff
            'department_id' => 1, // Sales
            'approval' => 0 // OFF
        ]);
        DB::table('users')->insert([
            'name' => 'user2',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => 2, // GM
            'department_id' => 1, // Sales
            'approval' => 1 // ON
        ]);

        /**
         * Budgets
         */
        DB::table('budgets')->insert([
            'budget_type' => 0,
            'cost_type' => 0,
            'position' => 2,
            'amount' => 4000000
        ]);
        DB::table('budgets')->insert([
            'budget_type' => 0,
            'cost_type' => 0,
            'position' => 3,
            'amount' => 10000000
        ]);
        DB::table('budgets')->insert([
            'budget_type' => 0,
            'cost_type' => 1,
            'position' => 2,
            'amount' => 5000000
        ]);
        DB::table('budgets')->insert([
            'budget_type' => 0,
            'cost_type' => 1,
            'position' => 3,
            'amount' => 15000000
        ]);

        DB::table('budgets')->insert([
            'budget_type' => 1,
            'cost_type' => 0,
            'position' => 0,
            'amount' => 4000000
        ]);
        DB::table('budgets')->insert([
            'budget_type' => 1,
            'cost_type' => 0,
            'position' => 1,
            'amount' => 10000000
        ]);
        DB::table('budgets')->insert([
            'budget_type' => 1,
            'cost_type' => 1,
            'position' => 0,
            'amount' => 5000000
        ]);
        DB::table('budgets')->insert([
            'budget_type' => 1,
            'cost_type' => 1,
            'position' => 1,
            'amount' => 15000000
        ]);

        /**
         * companies
         */
        DB::table('companies')->insert([
            'company_name' => 'SBA',
            'attendants_name' => 'Tí, Sửu, Dần',
            'email' => 'sba@gmail.com'
        ]);
        DB::table('companies')->insert([
            'company_name' => 'Nokia',
            'attendants_name' => 'Micheal',
            'email' => 'micheal@gmail.com'
        ]);
    }
}
