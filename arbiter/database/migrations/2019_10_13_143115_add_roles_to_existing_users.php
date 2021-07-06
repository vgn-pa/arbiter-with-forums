<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Role;
use App\User;

class AddRolesToExistingUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $memberRole = Role::where('name', 'Member')->first();

        $users = User::all();

        foreach($users as $user) {
            if($user->is_admin) {
                $user->role_id = $adminRole->id;
            } else {
                $user->role_id = $memberRole->id;
            }
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
