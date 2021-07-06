<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvgsToAllianceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alliance_history', function (Blueprint $table) {
            $table->bigInteger('avg_size');
            $table->bigInteger('avg_value');
            $table->bigInteger('avg_score');
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
