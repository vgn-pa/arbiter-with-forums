<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUrlToScanIdAndMakeUniqueOnScanQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('scan_queue')->truncate();

        Schema::table('scan_queue', function (Blueprint $table) {
            $table->renameColumn('url', 'scan_id');
            $table->unique('scan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scan_queue', function (Blueprint $table) {
            $table->renameColumn('scan_id', 'url');
            $table->dropUnique('scan_queue_scan_id_unique');
        });
    }
}
