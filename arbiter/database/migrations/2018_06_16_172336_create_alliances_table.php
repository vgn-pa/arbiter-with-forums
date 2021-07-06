<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('alliances', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('rank');
          $table->string('name');
          $table->integer('size');
          $table->integer('members');
          $table->bigInteger('counted_score');
          $table->bigInteger('points');
          $table->bigInteger('total_score');
          $table->bigInteger('total_value');
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
        Schema::drop('alliances');
    }
}
