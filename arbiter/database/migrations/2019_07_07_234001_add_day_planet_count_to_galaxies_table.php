<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDayPlanetCountToGalaxiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaxies', function (Blueprint $table) {
            $table->integer('day_planet_count')->default(0);
            $table->integer('growth_planet_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galaxies', function (Blueprint $table) {
            $table->dropColumn('day_planet_count');
            $table->dropColumn('growth_planet_count');
        });
    }
}
