<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTableV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedTinyInteger('budget_type')->comment('0：BUSINESS TRIP | 1：ENTERTAINMENT FEE');
            $table->unsignedTinyInteger('step_type')->comment('1 : Application(Step 1) , 2 : Settlement(Step 2)');
            $table->unsignedTinyInteger('position')->comment('1:PO 2:Not PO 3:Economy class 4:Bussiness class');
            $table->decimal('amount', 11, 0)->unsigned()->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}
