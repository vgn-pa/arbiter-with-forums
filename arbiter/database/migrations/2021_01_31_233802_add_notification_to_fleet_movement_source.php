<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationToFleetMovementSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE fleet_movements CHANGE COLUMN source source ENUM('incoming', 'launch', 'jgp', 'parser', 'notification') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE fleet_movements CHANGE COLUMN source source ENUM('incoming', 'launch', 'jgp', 'parser') NOT NULL");
    }
}
