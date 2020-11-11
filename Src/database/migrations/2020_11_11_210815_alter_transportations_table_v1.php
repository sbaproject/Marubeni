<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransportationsTableV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportations', function (Blueprint $table) {
            $table->string('departure')->nullable()->change();
            $table->string('arrive')->nullable()->change();
            $table->string('method')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transportations', function (Blueprint $table) {
            $table->string('departure')->change();
            $table->string('arrive')->change();
            $table->string('method')->change();
        });
    }
}
