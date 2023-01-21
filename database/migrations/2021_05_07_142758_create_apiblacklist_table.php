<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiblacklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apiblacklist', function (Blueprint $table) {
            $table->integer('APIBlacklistId');
            $table->string('IP', 50);
            $table->timestamp('BlacklistedOn')->useCurrent();
            $table->dateTime('UnblacklistAt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apiblacklist');
    }
}
