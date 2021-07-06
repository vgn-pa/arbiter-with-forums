<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Planet;
use App\Tick;

class AddSignupTickToPlanetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planets', function (Blueprint $table) {
            $table->integer('age')->default(0);
        });

        $planets = Planet::withCount('history')->get();

        foreach($planets as $planet) {
            $planet->age = $planet->history_count;
            $planet->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planets', function (Blueprint $table) {
            $table->dropColumn('age');
        });
    }
}
