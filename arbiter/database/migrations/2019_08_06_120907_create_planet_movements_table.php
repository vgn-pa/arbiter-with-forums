<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanetMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planet_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('planet_id');
            $table->integer('from_x')->nullable();
            $table->integer('from_y')->nullable();
            $table->integer('from_z')->nullable();
            $table->integer('to_x');
            $table->integer('to_y');
            $table->integer('to_z');
            $table->integer('tick');
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
        Schema::dropIfExists('planet_movements');
    }
}
