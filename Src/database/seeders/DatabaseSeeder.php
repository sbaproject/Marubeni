<?php

namespace Database\Seeders;

use App\Models\Department;
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
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            FormSeeder::class,
            ApplicantSeeder::class,
            //BudgetSeeder::class,
            //GroupSeeder::class,
            ApplicationSeeder::class,
            //LeaveSeeder::class,
            //FlowSeeder::class,
            //StepSeeder::class,
            CompanySeeder::class,
        ]);
    }
}
