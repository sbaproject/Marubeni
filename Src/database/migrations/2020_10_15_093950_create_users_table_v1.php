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
        Schema::create('m_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('department_id')->unsigned();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('location')->unsigned();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('m_users');
            $table->foreign('updated_by')->references('id')->on('m_users');
            $table->foreign('deleted_by')->references('id')->on('m_users');
            $table->foreign('department_id')->references('id')->on('m_departments');
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
