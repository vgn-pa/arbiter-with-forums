<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoresToAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alliances', function (Blueprint $table) {
            $table->bigInteger('size')->default(0);
            $table->bigInteger('score')->default(0);
            $table->bigInteger('value')->default(0);
            $table->bigInteger('avg_size')->default(0);
            $table->bigInteger('avg_value')->default(0);
            $table->bigInteger('avg_score')->default(0);
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
