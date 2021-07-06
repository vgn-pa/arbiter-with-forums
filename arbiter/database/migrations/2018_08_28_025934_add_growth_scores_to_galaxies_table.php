<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGrowthScoresToGalaxiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaxies', function (Blueprint $table) {
            $table->bigInteger('growth_value')->default(0);
            $table->bigInteger('growth_score')->default(0);
            $table->bigInteger('growth_size')->default(0);
            $table->bigInteger('growth_xp')->default(0);
            $table->integer('growth_rank_value')->default(0);
            $table->integer('growth_rank_score')->default(0);
            $table->integer('growth_rank_size')->default(0);
            $table->integer('growth_rank_xp')->default(0);
            $table->float('growth_percentage_value')->default(0);
            $table->float('growth_percentage_score')->default(0);
            $table->float('growth_percentage_size')->default(0);
            $table->float('growth_percentage_xp')->default(0);
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
            $table->dropColumn('growth_value');
            $table->dropColumn('growth_score');
            $table->dropColumn('growth_size');
            $table->dropColumn('growth_xp');
            $table->dropColumn('growth_rank_value');
            $table->dropColumn('growth_rank_score');
            $table->dropColumn('growth_rank_size');
            $table->dropColumn('growth_rank_xp');
            $table->dropColumn('growth_percentage_value');
            $table->dropColumn('growth_percentage_score');
            $table->dropColumn('growth_percentage_size');
            $table->dropColumn('growth_percentage_xp');
        });
    }
}
