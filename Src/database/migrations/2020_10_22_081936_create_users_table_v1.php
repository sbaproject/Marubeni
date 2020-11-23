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
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->unsignedTinyInteger('role')->comment('99:Admin, 1:Staff , 2:GM, 3:PIC, 4:DGD, 5:GD');
            $table->unsignedTinyInteger('location')->default(0)->comment('0: Ha Noi, 1: Ho Chi minh');
            $table->unsignedInteger('department_id');
            $table->unsignedTinyInteger('approval')->comment('0: OFF, 1: ON');
            $table->integer('leave_days')->nullable()->default(0);
            $table->integer('leave_lemaining_days')->nullable()->default(0);
            $table->integer('leave_lemaining_time')->nullable()->default(0);
            $table->text('memo')->nullable();
            $table->string('locale')->default('en')->comment('Current selected language of user');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('otp_token')->nullable()->comment('used to verify user when user logged in from external network');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

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
