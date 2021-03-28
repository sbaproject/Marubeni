<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableV2 extends Migration
{
    
    private $table = 'users';
    private $newColumn = 'super_admin_flg';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn($this->table, $this->newColumn)){
            return;
        }

        Schema::table($this->table, function (Blueprint $tb) {
            $tb->unsignedTinyInteger($this->newColumn)->after('role')->default(0)->comment('0: False 1:True - Determine super admin of system.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn($this->table, $this->newColumn)) {
            return;
        }

        Schema::table($this->table, function (Blueprint $tb) {
            $tb->dropColumn($this->newColumn);
        });
    }
}
