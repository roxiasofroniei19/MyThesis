<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigInteger('ClientId', true);
            $table->string('ClientName');
            $table->string('ClientAddress');
            $table->string('ClientPhone');
            $table->string('ClientVATNumber');
            $table->string('ClientRegistrationNumber');
            $table->timestamp('DateAdded')->useCurrent();
            $table->integer('accounts_AccountId')->default(0)->comment('Account Id who created this client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
