<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEntertainmentinfosAddEt2id extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('entertaiment_infos','entertainment2_id')){
            return;
        }
        Schema::table('entertaiment_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('entertaiment_id')->nullable()->change();
            $table->unsignedBigInteger('entertainment2_id')->after('entertaiment_id')->nullable();

            $table->foreign('entertainment2_id')->references('id')->on('entertainment2s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('entertaiment_infos', 'entertainment2_id')) {
            return;
        }
        Schema::table('entertainment2s', function (Blueprint $table) {
            $table->dropForeign(['entertaiment_infos_entertainment2_id_id_foreign']);
        });
    }
}
