<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimingsToTicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticks', function (Blueprint $table) {
            $table->bigInteger('planet_time')->default(0);
            $table->bigInteger('galaxy_time')->default(0);
            $table->bigInteger('alliance_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticks', function (Blueprint $table) {
            //
        });
    }
}
