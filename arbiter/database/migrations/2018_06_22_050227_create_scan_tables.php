<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScanTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scan_type');
            $table->string('pa_id');
            $table->integer('planet_id');
            $table->integer('tick');
            $table->timestamps();
        });

        Schema::create('planet_scans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scan_id');
            $table->string('roid_metal');
            $table->string('roid_crystal');
            $table->string('roid_eonium');
            $table->string('res_metal');
            $table->string('res_crystal');
            $table->string('res_eonium');
            $table->string('factory_usage_light');
            $table->string('factory_usage_medium');
            $table->string('factory_usage_heavy');
            $table->string('prod_res');
            $table->string('agents');
            $table->string('guards');
            $table->timestamps();
        });

        Schema::create('development_scans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scan_id');
            $table->integer('light_factory');
            $table->integer('medium_factory');
            $table->integer('heavy_factory');
            $table->integer('wave_amplifier');
            $table->integer('wave_distorter');
            $table->integer('metal_refinery');
            $table->integer('crystal_refinery');
            $table->integer('eonium_refinery');
            $table->integer('research_lab');
            $table->integer('finance_centre');
            $table->integer('security_centre');
            $table->integer('military_centre');
            $table->integer('structure_defence');
            $table->integer('travel');
            $table->integer('infrastructure');
            $table->integer('hulls');
            $table->integer('waves');
            $table->integer('core');
            $table->integer('covert_op');
            $table->integer('mining');
            $table->integer('population');
            $table->timestamps();
        });

        Schema::create('unit_scans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scan_id');
            $table->integer('ship_id');
            $table->bigInteger('amount');
            $table->timestamps();
        });

        Schema::create('advanced_unit_scans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scan_id');
            $table->integer('ship_id');
            $table->bigInteger('amount');
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
        Schema::dropIfExists('scans');
        Schema::dropIfExists('planet_scans');
        Schema::dropIfExists('development_scans');
        Schema::dropIfExists('unit_scans');
        Schema::dropIfExists('advanced_unit_scans');
    }
}
