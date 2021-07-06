<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScanRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scan_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scan_type');
            $table->integer('planet_id');
            $table->integer('user_id');
            $table->integer('tick');
            $table->integer('scan_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scan_requests');
    }
}
