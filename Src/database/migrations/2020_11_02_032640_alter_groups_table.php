<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->unsignedInteger('budget_id')->nullable()->after('id');
            $table->tinyInteger('budget_type_compare')->nullable()->after('budget_id')->comment('0 : less or equal than budget amount, 1 :  greater than budget amount  ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('budget_id');
            $table->dropColumn('budget_type_compare');
        });
    }
}
