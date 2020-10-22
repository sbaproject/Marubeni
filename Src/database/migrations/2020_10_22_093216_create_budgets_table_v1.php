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
            $table->id();
            $table->unsignedTinyInteger('budget_type')->comment('0：BUSINESS TRIP | 1：ENTERTAINMENT FEE');
            $table->unsignedTinyInteger('cost_type')->comment('0：Assignment | 1：Settlement');
            $table->unsignedTinyInteger('position')->comment('0：PO | 1：NOT PO | 2:Economy | 3:Business');
            $table->decimal('amount', 11, 0)->unsigned();
            $table->timestamps();
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
