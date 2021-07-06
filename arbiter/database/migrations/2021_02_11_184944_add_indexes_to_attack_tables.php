<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToAttackTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attacks', function (Blueprint $table) {
            $table->index('attack_id');
        });
        Schema::table('attack_targets', function (Blueprint $table) {
            $table->index('attack_id');
            $table->index('planet_id');
        });
        Schema::table('attack_bookings', function (Blueprint $table) {
            $table->index('attack_id');
            $table->index('attack_target_id');
            $table->index('user_id');
        });
        Schema::table('attack_booking_users', function (Blueprint $table) {
            $table->index('booking_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attacks', function (Blueprint $table) {
            $table->dropIndex(['attack_id']);
        });
        Schema::table('attack_targets', function (Blueprint $table) {
            $table->dropIndex(['attack_id']);
            $table->dropIndex(['planet_id']);
        });
        Schema::table('attack_bookings', function (Blueprint $table) {
            $table->dropIndex(['attack_id']);
            $table->dropIndex(['attack_target_id']);
            $table->dropIndex(['user_id']);
        });
        Schema::table('attack_booking_users', function (Blueprint $table) {
            $table->dropIndex(['booking_id']);
            $table->dropIndex(['user_id']);
        });
    }
}
