<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangesToPlanetHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planet_history', function (Blueprint $table) {
            $table->bigInteger('change_value')->default(0);
            $table->bigInteger('change_score')->default(0);
            $table->bigInteger('change_xp')->default(0);
            $table->bigInteger('change_size')->default(0);
            $table->bigInteger('rank_value')->default(0);
            $table->bigInteger('rank_score')->default(0);
            $table->bigInteger('rank_xp')->default(0);
            $table->bigInteger('rank_size')->default(0);
        });

        Schema::table('galaxy_history', function (Blueprint $table) {
            $table->bigInteger('change_value')->default(0);
            $table->bigInteger('change_score')->default(0);
            $table->bigInteger('change_xp')->default(0);
            $table->bigInteger('change_size')->default(0);
            $table->bigInteger('rank_value')->default(0);
            $table->bigInteger('rank_score')->default(0);
            $table->bigInteger('rank_xp')->default(0);
            $table->bigInteger('rank_size')->default(0);
        });

        Schema::table('alliance_history', function (Blueprint $table) {
            $table->bigInteger('change_value')->default(0);
            $table->bigInteger('change_score')->default(0);
            $table->bigInteger('change_xp')->default(0);
            $table->bigInteger('change_size')->default(0);
            $table->bigInteger('rank_value')->default(0);
            $table->bigInteger('rank_score')->default(0);
            $table->bigInteger('rank_xp')->default(0);
            $table->bigInteger('rank_size')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planet_history', function (Blueprint $table) {
            //
        });
    }
}
