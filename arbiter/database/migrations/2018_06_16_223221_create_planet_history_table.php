<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanetHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('planet_history', function (Blueprint $table) {
          $table->increments('id');
          $table->string('planet_id');
          $table->integer('x');
          $table->integer('y');
          $table->integer('z');
          $table->string('planet_name');
          $table->string('ruler_name');
          $table->string('race');
          $table->integer('size');
          $table->bigInteger('score');
          $table->bigInteger('value');
          $table->bigInteger('xp');
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
        //
    }
}
