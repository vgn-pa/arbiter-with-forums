<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGrowthPercentageColumnsToBattlegroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('battlegroups', function (Blueprint $table) {
            $table->float('growth_percentage_value')->default(0);
            $table->float('growth_percentage_score')->default(0);
            $table->float('growth_percentage_size')->default(0);
            $table->float('growth_percentage_xp')->default(0);
            $table->bigInteger('growth_value')->default(0);
            $table->bigInteger('growth_score')->default(0);
            $table->bigInteger('growth_size')->default(0);
            $table->bigInteger('growth_xp')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('battlegroups', function (Blueprint $table) {
            //
        });
    }
}
