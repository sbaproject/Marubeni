<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEntertainmentsTableV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entertainments', function (Blueprint $table) {
            $table->unsignedTinyInteger('entertainment_reason')->after('project_name')->comment('Dinner (private sector); Dinner (PO); Golf (private)-AH burden; Golf (Private)-Sales Department Burden; Golf (PO); Gift (President and above); Gift (Specific Director or Executive Officer); Gifts (Other Directors or Executive Officers); Gift (manager or person in charge); Other');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entertainments', function (Blueprint $table) {
            //
        });
    }
}
