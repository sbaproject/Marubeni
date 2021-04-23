<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntertainment2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entertainment2s', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('application_id')->unique();
            $table->string('entertainment_dt')->nullable();
            $table->decimal('est_amount', 11, 0)->nullable();
            $table->string('charged_to')->nullable()->comment('Format data: [department_id_1]-[value1%],[department_id_2]-[value2%],...');
            $table->string('pay_info')->nullable();
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
        Schema::dropIfExists('entertainment2s');
    }
}
