<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_approval', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedBigInteger('approved_by');
            $table->unsignedInteger('application_id');
            $table->tinyInteger('status')->comment('0:applying, -1:declined, -2:reject, -3:draft, 1->5:approved, 99:completed');
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users');
            $table->foreign('application_id')->references('id')->on('applications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_approval');
    }
}