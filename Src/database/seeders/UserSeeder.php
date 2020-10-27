<?php

namespace Database\Seeders;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $department_ids = Arr::pluck(Department::select('id')->get()->toArray(), 'id');

        DB::table('users')->truncate();

        /**
         * users
         */
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            // 'role_id' => 6, // admin
            'role' => 99, // admin
            'department_id' => 3, // ADMI
            'approval' => 0, // OFF
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $firstId = DB::table('users')->first('id')->id;

        DB::table('users')->insert([
            'name' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('123'),
            // 'role_id' => 1, // Staff
            'role' => 1, // Staff
            'department_id' => 1, // Sales
            'approval' => 0, // OFF
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $firstId,
            'updated_by' => $firstId
        ]);
        DB::table('users')->insert([
            'name' => 'user2',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('123'),
            // 'role_id' => 2, // GM
            'role' => 2, // GM
            'department_id' => 1, // Sales
            'approval' => 1, // ON
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $firstId,
            'updated_by' => $firstId
        ]);

        for ($i = 5; $i < 100; $i++) {
            $role = Arr::random(config('const.role'));
            $approval = $role == 1 ? config('const.approval.off') : Arr::random(config('const.approval'));
            DB::table('users')->insert([
                'name' => $faker->name(),
                'email' => $faker->email(),
                'password' => Hash::make('123'),
                'role' => $role,
                'location' => Arr::random(config('const.location')),
                'department_id' => Arr::random($department_ids),
                'approval' => $approval,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => $firstId,
                'updated_by' => $firstId
            ]);
        }
    }
}
