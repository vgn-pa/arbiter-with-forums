<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDayScoresAndRanksToGalaxiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaxies', function (Blueprint $table) {
            $table->integer('day_rank_value')->default(0);
            $table->integer('day_rank_score')->default(0);
            $table->integer('day_rank_size')->default(0);
            $table->integer('day_rank_xp')->default(0);
            $table->bigInteger('day_value')->default(0);
            $table->bigInteger('day_score')->default(0);
            $table->bigInteger('day_size')->default(0);
            $table->bigInteger('day_xp')->default(0);
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
            $table->dropColumn('day_rank_value');
            $table->dropColumn('day_rank_score');
            $table->dropColumn('day_rank_size');
            $table->dropColumn('day_rank_xp');
            $table->dropColumn('day_value');
            $table->dropColumn('day_score');
            $table->dropColumn('day_size');
            $table->dropColumn('day_xp');
        });
    }
}
