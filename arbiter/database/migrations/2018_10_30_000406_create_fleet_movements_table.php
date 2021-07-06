<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFleetMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('launch_tick')->nullable();
            $table->string('fleet_name');
            $table->integer('planet_from_id');
            $table->integer('planet_to_id');
            $table->enum('mission_type', ['Attack', 'Defend'])->nullable();
            $table->integer('land_tick');
            $table->integer('tick');
            $table->integer('eta');
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
        Schema::dropIfExists('fleet_movements');
    }
}
