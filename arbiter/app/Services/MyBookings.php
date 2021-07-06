<?php

namespace App\Services;

use App\AttackBooking;
use App;
use App\Services\Misc\MakeBattleCalc;

class MyBookings
{
    protected $id;
    protected $userId;
    protected $attackId;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId($id)
    {
        $this->userId = $id;
        return $this;
    }

    public function setAttackId($id)
    {
        $this->attackId = $id;
        return $this;
    }

    public function execute()
    {
        $owner = AttackBooking::where('user_id', $this->userId)->get()->pluck('id');
        $sharedUser = AttackBooking::whereHas('users', function($q) {
            $q->where('user_id', $this->userId);
        })->get()->pluck('id');

        $userBookings = array_merge($owner->toArray(), $sharedUser->toArray());

        $bookings = AttackBooking::with([
          'target',
          'target.planet',
          'target.attack',
          'target.planet',
          'target.planet.alliance',
          'target.planet.latestP',
          'target.planet.latestP.scan',
          'target.planet.latestD',
          'target.planet.latestD.scan',
          'target.planet.latestJ',
          'target.planet.latestJ.scan',
          'target.planet.latestU',
          'target.planet.latestU.u',
          'target.planet.latestU.u.ship',
          'target.planet.latestA',
          'target.planet.latestA.au',
          'user',
          'users'
        ])->orderBy('land_tick', 'ASC')->whereIn('id', $userBookings);

        $bookings = $bookings->whereHas('target', function($q1) {
            $q1->whereHas('attack', function($q2) {
                $q2->where('is_closed', 0);
                if($this->attackId) {
                    $q2->where('attack_id', $this->attackId);
                }
            });
        });

        if($this->id) {
            $booking = $bookings->find($this->id);

            $calc = App::make(MakeBattleCalc::class);
            $booking->target->calc = $calc->setX($booking->target->planet->x)
              ->setY($booking->target->planet->y)
              ->setZ($booking->target->planet->z)
              ->execute();

            return $booking;
        } else {
            $bookings = $bookings->get();
            foreach($bookings as $booking) {
                $calc = App::make(MakeBattleCalc::class);
                $booking->target->calc = $calc->setX($booking->target->planet->x)
                  ->setY($booking->target->planet->y)
                  ->setZ($booking->target->planet->z)
                  ->execute();
            }
            return $bookings;
        }
    }
}
