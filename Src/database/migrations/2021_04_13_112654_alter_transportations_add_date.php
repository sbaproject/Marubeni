<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransportationsAddDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('transportations','trans_date')){
            return;
        }
        Schema::table('transportations', function (Blueprint $table) {
            $table->date('trans_date')->nullable()->after('businesstrip_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('transportations', 'trans_date')) {
            return;
        }
        Schema::table('transportations', function (Blueprint $table) {
            $table->dropColumn('trans_date');
        });
    }
}
