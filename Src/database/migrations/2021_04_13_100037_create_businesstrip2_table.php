<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinesstrip2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesstrip2s', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('application_id')->unique();
            $table->string('destinations')->nullable();
            $table->integer('number_of_days')->default(0)->nullable();
            $table->decimal('daily1_amount', 13, 2)->default(0)->nullable();
            $table->integer('daily1_days')->nullable();
            $table->decimal('daily2_amount', 13, 2)->default(0)->nullable();
            $table->decimal('daily2_rate', 11, 0)->default(0)->nullable();
            $table->integer('daily2_days')->nullable();
            $table->string('charged_to')->comment('Format data: [department_id_1]-[value1%],[department_id_2]-[value2%],...')->nullable();
            $table->text('file_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('charged_to')->references('id')->on('departments');
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
        Schema::dropIfExists('businesstrip2s');
    }
}
