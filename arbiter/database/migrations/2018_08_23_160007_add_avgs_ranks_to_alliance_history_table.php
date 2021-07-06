<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvgsRanksToAllianceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alliance_history', function (Blueprint $table) {
            $table->bigInteger('rank_avg_size')->default(0);
            $table->bigInteger('rank_avg_value')->default(0);
            $table->bigInteger('rank_avg_score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alliance_history', function (Blueprint $table) {
            //
        });
    }
}
