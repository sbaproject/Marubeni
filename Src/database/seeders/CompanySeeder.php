<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $data = [];
        for ($i=0; $i < 100; $i++) { 
            $item['name'] = $faker->company;
            $item['attendants_name'] = $faker->name;
            $item['email'] = $faker->email;
            $item['created_at'] = Carbon::now();
            $item['updated_at'] = Carbon::now();
            $data[] = $item;
        }
        DB::table('companies')->insert($data);
    }
}
