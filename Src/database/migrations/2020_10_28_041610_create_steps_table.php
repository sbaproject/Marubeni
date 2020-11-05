<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('flow_id');
            $table->unsignedBigInteger('approver_id')->comment('User ID');
            $table->tinyInteger('approver_type')->default(0)->comment('0 : TO , 1 : CC');
            $table->tinyInteger('step_type')->default(1)->comment('1 : Application(Step 1) , 2 : Settlement(Step 2)');
            $table->tinyInteger('order')->default(1)->comment('using to update status on applications table when user approved');
            $table->tinyInteger('select_order')->default(1)->comment('using get list approved of user by status');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('flow_id')->references('id')->on('flows');
            $table->foreign('approver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steps');
    }
}
