<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplicationsTableNewReasonSubsequent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('applications', 'subsequent_reason')){
            return;
        }
        Schema::table('applications', function (Blueprint $table) {
            $table->text('subsequent_reason')->after('subsequent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('applications', 'subsequent_reason')) {
            return;
        }
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('subsequent_reason');
        });
    }
}
