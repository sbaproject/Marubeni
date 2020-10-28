<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntertaimentinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entertaiment_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entertaiment_id');
            $table->string('cp_name')->nullable();
            $table->string('cp_country')->nullable();
            $table->string('cp_address')->nullable();
            $table->string('cp_department')->nullable();
            $table->string('title')->nullable();
            $table->string('name_attendants')->nullable();
            $table->text('details_dutles')->nullable();
            $table->timestamps();

            $table->foreign('entertaiment_id')->references('id')->on('entertaiments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entertaiment_infos');
    }
}
