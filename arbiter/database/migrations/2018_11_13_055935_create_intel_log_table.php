<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntelLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intel_change', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('planet_id');
            $table->integer('alliance_from_id')->nullable();
            $table->integer('alliance_to_id')->nullable();
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
        Schema::dropIfExists('intel_change');
    }
}
