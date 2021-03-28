<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplicationsAddApplicationNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('applications','application_no')){
            return;
        }
        Schema::table('applications', function (Blueprint $table) {
            $table->string('application_no', 20)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('applications', 'application_no')) {
            return;
        }
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('application_no');
        });
    }
}
