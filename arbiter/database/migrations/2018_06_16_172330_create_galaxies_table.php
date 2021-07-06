<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalaxiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('galaxies', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('x');
          $table->integer('y');
          $table->string('galaxy_name');
          $table->bigInteger('size');
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
        Schema::drop('galaxies');
    }
}
