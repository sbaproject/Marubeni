<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripfeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tripfees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('businesstrip_id');
            $table->string('type_trip', 1)->nullable()->comment('A: Transportation, B: Accomodation, C:Communication');
            $table->string('trip_no', 3)->nullable()->comment('Like A1,A2,..,A10 | B1,B2,..,B10 | C1,C2,..,C10');
            $table->unsignedInteger('method')->nullable()->comment('1:Air-ticket 2:Train-Bus 3:Taxi 4:Trans-Others 5:Telephone-Internet 6:Communication-Others; 7:Accomodation Fees');
            $table->string('unit', 3)->nullable()->comment('Like USD,VND,SGD,...');
            $table->decimal('exchange_rate', 11 , 2)->default(0);
            $table->decimal('amount', 11, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('businesstrip_id')->references('id')->on('businesstrips2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tripfees');
    }
}
