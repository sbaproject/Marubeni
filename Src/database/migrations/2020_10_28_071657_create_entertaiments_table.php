<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntertaimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entertaiments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('application_id');
            $table->timestamp('entertainment_dt');
            $table->string('place')->nullable();
            $table->unsignedTinyInteger('during_trip')->nullable()->comment('1:YES -- 0:NO');
            $table->unsignedTinyInteger('check_row')->nullable()->comment('1:YES -- 0:NO');
            $table->integer('entertainment_times')->default(0)->nullable();
            $table->unsignedTinyInteger('has_entertainment_times')->nullable()->comment('1:YES -- 0:NO');
            $table->unsignedTinyInteger('existence_projects')->nullable()->comment('1:YES -- 0:NO');
            $table->unsignedTinyInteger('includes_family')->nullable()->comment('1:YES -- 0:NO');
            $table->string('project_name', 50)->nullable();
            // $table->text('entertainment_reason')->nullable();
            $table->unsignedTinyInteger('entertainment_reason')->nullable()->comment('1:Dinner (private sector); 2:Dinner (PO); 3:Golf (private)-AH burden; 4:Golf (Private)-Sales Department Burden; 5:Golf (PO); 6:Gift (President and above); 7:Gift (Specific Director or Executive Officer); 8:Gifts (Other Directors or Executive Officers); 9:Gift (manager or person in charge); 10:Other');
            $table->string('entertainment_reason_other')->nullable()->comment('Only for entertainment_reason = 10 (Other)');
            $table->unsignedInteger('entertainment_person')->default(0)->nullable();
            $table->decimal('est_amount', 11, 0)->nullable()->default(0);
            $table->text('reason_budget_over')->nullable();
            $table->text('file_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('application_id')->references('id')->on('applications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entertaiments');
    }
}
