<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTableV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->unsignedTinyInteger('location')->default(0)->comment('0: Ha Noi, 1: Ho Chi minh');
            $table->unsignedBigInteger('department_id');
            $table->unsignedTinyInteger('approval')->comment('0: OFF, 1: ON');
            $table->unsignedTinyInteger('leave_days')->nullable();
            $table->unsignedTinyInteger('leave_lemaining_days')->nullable();
            $table->unsignedTinyInteger('leave_lemaining_time')->nullable();
            $table->text('memo')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
