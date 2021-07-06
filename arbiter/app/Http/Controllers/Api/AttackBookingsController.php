<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Attack;
use App\PlanetHistory;
use App\AttackTarget;
use App\AttackBooking;
use Auth;
use App\User;
use App\Services\Misc\MakeBattleCalc;
use App\Services\MyBookings;
use App\Services\Booking\BookingUser;
use App\Services\Booking\BookingUserList;
use App;
use App\FleetMovement;

class AttackBookingsController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MyBookings $bookings)
    {
        return $bookings
                ->setUserId(Auth::user()->id)
                ->setAttackId($request->input('attack_id', null))
                ->execute();
    }

    public function update($id, Request $request) 
    {
        $booking = AttackBooking::where([
            'id' => $id,
            'user_id' => Auth::user()->id
        ])->orWhereHas('users', function($q) {
            $q->user_id = Auth::user()->id;
        })->first();

        if(!$booking) return response()->json(['error' => "Only owners or users of this booking can edit it."], 422);
                 
        AttackBooking::where('id', $id)->update([
            'notes' => $request->input('notes', null),
            'battle_calc'  => $request->input('battle_calc', null)
        ]);
    }

    public function show($id, Request $request, MyBookings $bookings)
    {
        return $bookings
                ->setId($id)
                ->setUserId(Auth::user()->id)
                ->setAttackId($request->input('attack_id', null))
                ->execute();
    }

    public function users($id, Request $request, BookingUserList $users)
    {
        return $users->setId($id)->users();
    }

    public function nonUsers($id, Request $request, BookingUserList $users)
    {
        return $users->setId($id)->nonUsers();
    }

    public function addUser($id, Request $request, BookingUser $booking)
    {
        return $booking->setId($id)
                ->setUserId($request->input('user_id'))
                ->add();
    }

    public function removeUser($id, Request $request, BookingUser $booking)
    {
        return $booking->setId($id)
                ->setUserId($request->input('user_id'))
                ->delete();
    }

    public function fleets($id, Request $request)
    {
        $booking = AttackBooking::with([
            'target', 
            'target.planet' => function($q) {
                $q->select(['id', 'x', 'y', 'z']);
            },
        ])->find($id);

        $fleets = FleetMovement::where('planet_to_id', $booking->target->planet_id)->with([
            'planetFrom' => function($q) {
                $q->select(['id', 'x', 'y', 'z', 'race', 'alliance_id', 'latest_p', 'latest_d', 'latest_u', 'latest_n', 'latest_j', 'latest_au']);
            },
            'planetFrom.latestP',
            'planetFrom.latestP.scan',
            'planetFrom.latestD',
            'planetFrom.latestD.scan',
            'planetFrom.latestJ',
            'planetFrom.latestJ.scan',
            'planetFrom.latestU',
            'planetFrom.latestU.u',
            'planetFrom.latestU.u.ship',
            'planetFrom.latestA',
            'planetFrom.latestA.au',
        ])->where('land_tick', $booking->land_tick)->orderBy('mission_type')->orderBy('fleet_name')->get();

        $target = $booking->target->planet;
        $attacks = [];
        $defence = [];

        foreach($fleets as $fleet) {
            if($fleet->mission_type == 'Attack') {
                $attacks[] = $fleet->planetFrom->x . ":" . $fleet->planetFrom->y . ":" . $fleet->planetFrom->z;
            }
            if($fleet->mission_type == 'Defend') {
                $defence[] = $fleet->planetFrom->x . ":" . $fleet->planetFrom->y . ":" . $fleet->planetFrom->z;
            }
        }

        $calc = App::make(MakeBattleCalc::class);

        $calc = $calc->setX($target->x)
            ->setY($target->y)
            ->setZ($target->z)
            ->setAttPlanets(implode(" ", $attacks))
            ->setDefPlanets(implode(" ", $defence))
            ->execute();

        return collect([
            'fleets' => $fleets,
            'calc' => $calc
        ]);
    }

    public function owner($id, Request $request) 
    {
        $userId = $request->input('user_id');
        $booking = AttackBooking::find($id);

        $user = User::find($userId);

        if($user && $booking->user_id == Auth::user()->id) {
            $booking->user_id = $user->id;
            $booking->save();
    
            $booking->users()->detach($userId);
            $booking->users()->syncWithoutDetaching([Auth::user()->id]);
        }
    }

    public function drop($bookingId, MyBookings $bookings, Request $request, BookingUser $bookingUser)
    {
        $booking = AttackBooking::with('users')->where('id', $bookingId)->first();

        if($booking) {

            if($booking->user_id == Auth::user()->id && count($booking->users)) {
                return response()->json(['error' => "Change owner before dropping target."], 422);
            }

            // If the booking owner is the same as the user dropping the target and theres no users shared
            if($booking->user_id == Auth::user()->id && !count($booking->users)) {
                $booking->user_id = null;
                $booking->save();
            } else {
                // Check if user dropping is one of the shared users
                $booking = AttackBooking::whereHas('users', function($q) {
                    $q->where('user_id', Auth::user()->id);
                })->first();

                if($booking) {
                    $booking->users()->detach(Auth::user()->id);
                }
            }
        }

        return $bookings
                ->setUserId(Auth::user()->id)
                ->setAttackId($request->input('attack_id', ''))
                ->execute();
    }
}
