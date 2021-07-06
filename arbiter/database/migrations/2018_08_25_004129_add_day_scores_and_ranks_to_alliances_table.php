<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDayScoresAndRanksToAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alliances', function (Blueprint $table) {
            $table->integer('day_rank_value')->default(0);
            $table->integer('day_rank_score')->default(0);
            $table->integer('day_rank_size')->default(0);
            $table->integer('day_rank_avg_value')->default(0);
            $table->integer('day_rank_avg_score')->default(0);
            $table->integer('day_rank_avg_size')->default(0);
            $table->bigInteger('day_value')->default(0);
            $table->bigInteger('day_score')->default(0);
            $table->bigInteger('day_size')->default(0);
            $table->bigInteger('day_avg_value')->default(0);
            $table->bigInteger('day_avg_score')->default(0);
            $table->bigInteger('day_avg_size')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alliances', function (Blueprint $table) {
            $table->dropColumn('day_rank_value');
            $table->dropColumn('day_rank_score');
            $table->dropColumn('day_rank_size');
            $table->dropColumn('day_rank_avg_value');
            $table->dropColumn('day_rank_avg_score');
            $table->dropColumn('day_rank_avg_size');
            $table->dropColumn('day_value');
            $table->dropColumn('day_score');
            $table->dropColumn('day_size');
            $table->dropColumn('day_avg_value');
            $table->dropColumn('day_avg_score');
            $table->dropColumn('day_avg_size');
        });
    }
}
