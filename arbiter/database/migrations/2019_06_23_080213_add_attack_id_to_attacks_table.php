<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttackIdToAttacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attacks', function (Blueprint $table) {
            $table->string('attack_id');
        });

        \DB::statement('UPDATE attacks SET attack_id = id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attacks', function (Blueprint $table) {
            $table->dropColumn('attack_id');
        });
    }
}
