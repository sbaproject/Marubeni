<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('leave_lemaining_days','leave_remaining_days')->nullable()->default(0);
            $table->renameColumn('leave_lemaining_time','leave_remaining_time')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('leave_remaining_days', 'leave_lemaining_days')->nullable()->default(0);
            $table->renameColumn('leave_remaining_time', 'leave_lemaining_time')->nullable()->default(0);
        });
    }
}
