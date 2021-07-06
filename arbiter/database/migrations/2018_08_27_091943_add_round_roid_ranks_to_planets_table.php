<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoundRoidRanksToPlanetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planets', function (Blueprint $table) {
            $table->integer('rank_round_roids')->default(0);
            $table->integer('rank_lost_roids')->default(0);
            $table->integer('day_rank_round_roids')->default(0);
            $table->integer('day_rank_lost_roids')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planets', function (Blueprint $table) {
          $table->dropColumn('rank_round_roids');
          $table->dropColumn('rank_lost_roids');
          $table->dropColumn('day_rank_round_roids');
          $table->dropColumn('day_rank_lost_roids');
        });
    }
}
