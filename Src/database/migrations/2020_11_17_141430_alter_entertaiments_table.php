<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntertaimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('entertaiments', function (Blueprint $table) {
        //     $table->timestamp('entertainment_dt')->nullable()->change();
        // });
        DB::statement('ALTER TABLE `entertaiments` MODIFY `entertainment_dt` TIMESTAMP NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('entertaiments', function (Blueprint $table) {
        //     $table->timestamp('entertainment_dt')->nullable(false)->change();
        // });
        DB::statement('ALTER TABLE `entertaiments` MODIFY `entertainment_dt` TIMESTAMP NOT NULL;');
    }
}
