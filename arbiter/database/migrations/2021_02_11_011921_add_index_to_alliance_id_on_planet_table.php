<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToAllianceIdOnPlanetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planets', function (Blueprint $table) {
            $table->index('alliance_id');
            $table->index('galaxy_id');
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
            $table->dropIndex(['alliance_id']);
            $table->dropIndex(['galaxy_id']);
        });
    }
}
