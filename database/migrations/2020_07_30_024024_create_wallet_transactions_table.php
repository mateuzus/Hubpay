<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('refno');
            $table->string('narration');
            $table->unsignedBigInteger('record_type_id');
            $table->float('amount');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('wallet_transactions', function(Blueprint $table)
        {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('record_type_id')->references('id')->on('record_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('wallet_transactions');
    }
}
