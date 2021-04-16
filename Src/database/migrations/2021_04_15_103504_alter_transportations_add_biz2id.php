<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransportationsAddBiz2id extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('transportations','businesstrip2_id')){
            return;
        }
        Schema::table('transportations', function (Blueprint $table) {
            $table->unsignedBigInteger('businesstrip_id')->nullable()->change();
            $table->unsignedBigInteger('businesstrip2_id')->after('businesstrip_id')->nullable();

            $table->foreign('businesstrip2_id')->references('id')->on('businesstrips');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('transportations', 'businesstrip2_id')) {
            return;
        }
        Schema::table('transportations', function (Blueprint $table) {
            $table->dropForeign(['transportations_businesstrip2_id_foreign']);
        });
    }
}
