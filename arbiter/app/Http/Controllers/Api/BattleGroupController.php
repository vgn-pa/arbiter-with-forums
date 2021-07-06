<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\BattleGroup;
use App\User;
use Auth;

class BattleGroupController extends ApiController
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', '-avg_score');

        $bgs = BattleGroup::with('owner', 'members')->get();

        if($sort) {
            $sortDirection = substr($sort, 0, 1);
            $sort = substr($sort, 1);
        }

        if($sortDirection && $sortDirection == '+') {
            $sorted = $bgs->sortBy(function($bg) use($sort) {
                return $bg[$sort];
            });
        } else {
            $sorted = $bgs->sortByDesc(function($bg) use($sort) {
                return $bg[$sort];
            });
        }


        return $sorted->values();
    }

    public function store(Request $request)
    {
        $bg = BattleGroup::create([
            'name' => $request->input('name'),
            'creator_id' => Auth::user()->id
        ]);

        $bg->members()->sync([Auth::user()->id => ['is_pending' => 0]], false);
    }

    public function show($id)
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

    public function destroy($id)
    {
        BattleGroup::find($id)->delete();

        return $this->index();
    }

    public function join($id)
    {
        $bg = BattleGroup::find($id);

        $bg->members()->sync([Auth::user()->id => ['is_pending' => 1]], false);
    }
}
