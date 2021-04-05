<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHistoryApprovalAddSkip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('history_approval', 'skip_by')) {
            return;
        }
        Schema::table('history_approval', function (Blueprint $table) {
            $table->unsignedBigInteger('skiped_by')->after('comment')->nullable()->comment('Determine this approval is skiped by applicant');

            $table->foreign('skiped_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('history_approval', 'skip_by')) {
            return;
        }
        Schema::table(
            'history_approval',
            function (Blueprint $table) {
                $table->dropForeign(['applications_skiped_by_foreign']);
            }
        );
    }
}
