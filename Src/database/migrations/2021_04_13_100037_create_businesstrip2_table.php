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
            $table->decimal('total_daily_allowance', 11, 2)->default(0)->nullable();
            $table->string('total_daily_unit', 3)->nullable()->comment('Like USD,VND,SGD,...');
            $table->decimal('total_daily_rate', 11, 2)->default(0)->nullable();
            $table->decimal('daily_allowance', 11, 2)->default(0)->nullable();
            $table->string('daily_unit', 3)->nullable()->comment('Like USD,VND,SGD,...');
            $table->decimal('daily_rate', 11, 2)->default(0)->nullable();
            $table->decimal('other_fees', 11, 2)->default(0)->nullable();
            $table->string('other_fees_unit', 3)->nullable()->comment('Like USD,VND,SGD,...');
            $table->decimal('other_fees_rate', 11, 2)->default(0)->nullable();
            $table->text('other_fees_note')->nullable();
            $table->string('charged_to')->comment('Format data: [department_id_1]-[value1%],[department_id_2]-[value2%],...')->nullable();
            $table->date('under_instruction_date')->nullable();
            $table->string('under_instruction_approval_no', 20)->nullable();
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
