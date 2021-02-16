<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('wallet_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_no')->unique()->nullable();
            $table->string('Address')->nullable();
            $table->integer('pin')->unsigned()->nullable();
            $table->integer('bvn')->unsigned()->nullable();
            $table->string('identification')->nullable();
            $table->float('daily_limit', 8, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::table('wallet_profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('wallet_profiles');
    }
}
