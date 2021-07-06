<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanetCountAndPlanetCountChangeToGalaxyHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaxy_history', function (Blueprint $table) {
            $table->integer('planet_count')->default(0);
            $table->integer('change_planet_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galaxy_history', function (Blueprint $table) {
            $table->dropColumn('planet_count');
            $table->dropColumn('change_planet_count');
        });
    }
}
