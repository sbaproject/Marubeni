<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinesstripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesstrips', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('application_id');
            $table->string('destinations')->nullable();
            $table->date('trip_dt_from')->nullable();
            $table->date('trip_dt_to')->nullable();
            $table->string('accommodation')->nullable();
            $table->string('accompany')->nullable();
            $table->text('borne_by')->nullable();
            $table->text('comment')->nullable();
            $table->text('file_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('businesstrips');
    }
}
