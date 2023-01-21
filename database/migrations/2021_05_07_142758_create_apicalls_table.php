<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApicallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apicalls', function (Blueprint $table) {
            $table->integer('APICallsId');
            $table->timestamp('CallDate')->useCurrent();
            $table->string('IP', 50);
            $table->string('Method', 50);
            $table->text('DataReceived');
            $table->text('ResponseSent');
            $table->smallInteger('Status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apicalls');
    }
}
