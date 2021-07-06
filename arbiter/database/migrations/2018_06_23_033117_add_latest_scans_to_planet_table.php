<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatestScansToPlanetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('planets', function (Blueprint $table) {
          $table->integer('latest_p')->nullable();
          $table->integer('latest_d')->nullable();
          $table->integer('latest_u')->nullable();
          $table->integer('latest_au')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
