<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesAndCalcToAttackBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attack_bookings', function (Blueprint $table) {
            $table->text('notes')->nullable();
            $table->string('battle_calc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attack_bookings', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('battle_calc');
        });
    }
}
