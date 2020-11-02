<?php

namespace Database\Seeders;

use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

        DB::table('forms')->delete();

        $firstId = DB::table('users')->first('id')->id;

        /**
         * forms
         */
        DB::table('forms')->insert([
            'id' => 1,
            'name' => 'Leave',  
            'created_by' => $firstId,
            'created_at' => Carbon::now(),
        ]);

        DB::table('forms')->insert([
            'id' => 2,
            'name' => 'Biz Trip',  
            'created_by' => $firstId,
            'created_at' => Carbon::now(),
        ]);

        DB::table('forms')->insert([
            'id' => 3,
            'name' => 'Entertaiment',  
            'created_by' => $firstId,
            'created_at' => Carbon::now(),
        ]);
    }
}
