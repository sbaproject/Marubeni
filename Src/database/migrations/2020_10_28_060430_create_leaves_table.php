<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('application_id');
            $table->unsignedTinyInteger('code_leave')->nullable()->comment('0：ANNUAL LEAVE 1：UNPAID LEAVE 2：COMPASSIONATE LEAVE 3：WEDDING LEAVE 4：PERIODIC LEAVE 5：MATERNITY LEAVE 6：SICK LEAVE');
            $table->unsignedTinyInteger('paid_type')->nullable()->comment('1:YES 0:NO -- Required for code_leave = 6:SICK LEAVE');
            $table->text('reason_leave')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->date('time_day')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->date('maternity_from')->nullable();
            $table->date('maternity_to')->nullable();
            $table->integer('days_use')->nullable();
            $table->integer('times_use')->nullable();
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
        Schema::dropIfExists('leaves');
    }
}
