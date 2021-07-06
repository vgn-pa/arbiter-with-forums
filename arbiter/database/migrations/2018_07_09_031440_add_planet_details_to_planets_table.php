<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanetDetailsToPlanetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planets', function (Blueprint $table) {
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
