<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoresToGalaxiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaxies', function (Blueprint $table) {
            $table->bigInteger('size')->default(0);
            $table->bigInteger('score')->default(0);
            $table->bigInteger('value')->default(0);
            $table->bigInteger('xp')->default(0);

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
            //
        });
    }
}
