<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBattlegroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battlegroups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('creator_id');
            $table->bigInteger('value')->default(0);
            $table->bigInteger('score')->default(0);
            $table->bigInteger('size')->default(0);
            $table->bigInteger('xp')->default(0);
            $table->bigInteger('day_value')->default(0);
            $table->bigInteger('day_score')->default(0);
            $table->bigInteger('day_size')->default(0);
            $table->bigInteger('day_xp')->default(0);
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
        Schema::dropIfExists('battlegroups');
    }
}
