<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->integer('ScheduleId', true);
            $table->string('ScheduleName');
            $table->dateTime('ScheduleDateTime')->nullable();
            $table->date('ScheduleDate');
            $table->string('ScheduleTime');
            $table->string('ScheduleType')->nullable();
            $table->string('ScheduleAddress')->nullable();
            $table->longText('ScheduleDescription')->nullable();
            $table->bigInteger('accounts_AccountId')->comment('The account that created this schedule');
            $table->bigInteger('clients_ClientId')->comment('The client that is attached to this schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule');
    }
}
