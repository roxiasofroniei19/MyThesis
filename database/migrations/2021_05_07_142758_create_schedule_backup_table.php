<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleBackupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_backup', function (Blueprint $table) {
            $table->integer('ScheduleId')->primary();
            $table->string('ScheduleName');
            $table->date('ScheduleDate');
            $table->string('ScheduleTime');
            $table->longText('ScheduleDescription');
            $table->bigInteger('accounts_AccountId')->comment('The account who created this schedule');
            $table->bigInteger('clients_ClientId')->comment('The client who is attached to this schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_backup');
    }
}
