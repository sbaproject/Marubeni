<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('group_id');
            $table->unsignedTinyInteger('current_step')->comment('Normaly we have 2 steps (Step 1,2) per application, this field used to determined current step of application. But with [Leave] application only have default value is [2] (one step)');
            $table->tinyInteger('status')->default(0)->comment('0:applying, -1:declined, -2:reject, -3:draft, 1->5:approved, 99:completed');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('form_id')->references('id')->on('forms');
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
