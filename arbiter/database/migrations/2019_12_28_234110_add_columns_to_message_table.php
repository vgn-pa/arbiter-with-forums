<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message', function (Blueprint $table) {
            $table->text('forward_signature');
            $table->text('forward_sender_name');
            $table->bigInteger('edit_date');
            $table->text('author_signature');
            $table->text('caption_entities');
            $table->text('invoice')->nullable();
            $table->text('successful_payment')->nullable();
            $table->text('animation');
            $table->text('game');
            $table->text('poll');
            $table->text('passport_data')->nullable();
            $table->text('reply_markup')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message', function (Blueprint $table) {
            $table->dropColumn('forward_signature');
            $table->dropColumn('forward_sender_name');
            $table->dropColumn('edit_date');
            $table->dropColumn('author_signature');
            $table->dropColumn('caption_entities');
            $table->dropColumn('invoice');
            $table->dropColumn('successful_payment');
            $table->dropColumn('animation');
            $table->dropColumn('game');
            $table->dropColumn('poll');
            $table->dropColumn('passport_data');
            //$table->dropColumn('reply_markup');
        });
    }
}
