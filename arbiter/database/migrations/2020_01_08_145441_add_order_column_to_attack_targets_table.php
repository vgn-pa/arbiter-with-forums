<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Attack;
use App\AttackTarget;

class AddOrderColumnToAttackTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attack_targets', function (Blueprint $table) {
            $table->integer('order')->nullable();
        });

        $attacks = DB::table('attacks')->get();

        foreach($attacks as $attack) {
            $targets = AttackTarget::with('planet')->whereHas('planet')->where('attack_id', $attack->id)->get();

            foreach($targets as $target) {
                    $target->x = $target->planet->x;
                    $target->y = $target->planet->y;
                    $target->z = $target->planet->z;
            }

            $targets = array_orderby($targets->toArray(), 'x', SORT_ASC, 'y', SORT_ASC, 'z', SORT_ASC);

            $int = 1;

            foreach($targets as $target) {
                DB::table('attack_targets')->where('id', $target['id'])->update(['order' => $int++]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attack_targets', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
