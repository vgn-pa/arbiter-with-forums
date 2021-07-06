<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTelegramUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_update', function (Blueprint $table) {
            $table->bigInteger('channel_post_id')->nullable();
            $table->bigInteger('edited_channel_post_id')->nullable();
            $table->bigInteger('shipping_query_id')->nullable();
            $table->bigInteger('pre_checkout_query_id')->nullable();
            $table->bigInteger('poll_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_update', function (Blueprint $table) {
            $table->dropColumn('channel_post_id');
            $table->dropColumn('edited_channel_post_id');
            $table->dropColumn('shipping_query_id');
            $table->dropColumn('pre_checkout_query_id');
            $table->dropColumn('poll_id');
        });
    }
}
