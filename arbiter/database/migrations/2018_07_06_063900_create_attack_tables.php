<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttackTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('land_tick');
            $table->integer('waves');
            $table->timestamps();
        });

        Schema::create('attack_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attack_id');
            $table->integer('planet_id');
            $table->timestamps();
        });

        Schema::create('attack_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attack_id');
            $table->integer('attack_target_id');
            $table->integer('land_tick');
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
