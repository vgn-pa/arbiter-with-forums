<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('class');
            $table->string('t1');
            $table->string('t2')->nullable();
            $table->string('t3')->nullable();
            $table->string('type');
            $table->integer('init');
            $table->integer('guns');
            $table->integer('armor');
            $table->integer('damage')->nullable();
            $table->integer('empres');
            $table->integer('metal');
            $table->integer('crystal');
            $table->integer('eonium');
            $table->integer('total_cost');
            $table->string('race');
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
        Schema::dropIfExists('ships');
    }
}
