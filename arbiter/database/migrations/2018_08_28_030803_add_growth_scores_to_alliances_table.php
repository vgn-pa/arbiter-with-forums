<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGrowthScoresToAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alliances', function (Blueprint $table) {
            $table->bigInteger('growth_value')->default(0);
            $table->bigInteger('growth_score')->default(0);
            $table->bigInteger('growth_size')->default(0);
            $table->integer('growth_rank_value')->default(0);
            $table->integer('growth_rank_score')->default(0);
            $table->integer('growth_rank_size')->default(0);
            $table->float('growth_percentage_value')->default(0);
            $table->float('growth_percentage_score')->default(0);
            $table->float('growth_percentage_size')->default(0);
            $table->bigInteger('growth_avg_value')->default(0);
            $table->bigInteger('growth_avg_score')->default(0);
            $table->bigInteger('growth_avg_size')->default(0);
            $table->integer('growth_rank_avg_value')->default(0);
            $table->integer('growth_rank_avg_score')->default(0);
            $table->integer('growth_rank_avg_size')->default(0);
            $table->float('growth_percentage_avg_value')->default(0);
            $table->float('growth_percentage_avg_score')->default(0);
            $table->float('growth_percentage_avg_size')->default(0);
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
            //
        });
    }
}
