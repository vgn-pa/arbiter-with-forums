<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\BattleGroup;
use App\User;
use Auth;

class BattleGroupUserController extends ApiController
{
    public function index($id)
    {
        $bg = BattleGroup::with(['members', 'membersPending', 'members.planet','membersPending.planet'])->find($id);

        $members = $bg->members->keyBy('id')->toArray();
        $membersPending = $bg->membersPending->keyBy('id')->toArray();

        $users = User::whereNotNull('planet_id')->orderBy('name')->get()->keyBy('id')->toArray();

        $new = array_diff_key($users, $members);
        $new = array_diff_key($new, $membersPending);

        $bg->non_members = collect($new)->values();

        return $bg;
    }

    public function store($id, Request $request)
    {
        $user = $request->input('user');

        $bg = BattleGroup::find($id);

        $bg->members()->sync([$user => ['is_pending' => 0]], false);

        return $this->index($id);
    }

    public function accept($bgId, $userId)
    {
        $bg = BattleGroup::find($bgId);

        $bg->members()->sync([$userId => ['is_pending' => 0]], false);

        return $this->index($bgId);
    }

    public function decline($bgId, $userId)
    {
        $bg = BattleGroup::find($bgId);

        $bg->members()->detach([$userId]);

        return $this->index($bgId);
    }

    public function destroy($bgId, $userId)
    {
        $bg = BattleGroup::find($bgId);

        $bg->members()->detach([$userId]);

        return $this->index($bgId);
    }
}
