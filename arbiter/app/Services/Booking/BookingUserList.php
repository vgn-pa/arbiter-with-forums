<?php

namespace App\Services\Booking;

use App\AttackBooking;
use App\User;

class BookingUserList
{
    protected $id;
    protected $userId;

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

    public function users()
    {
        $booking = AttackBooking::with('users')->find($this->id);

        $users = [$booking->user_id];

        foreach($booking->users as $user) {
            $users[] = $user->id;
        }

        return User::whereIn('id', $users)->get();
    }

    public function nonUsers()
    {
        $booking = AttackBooking::with('users')->find($this->id);

        $users = [$booking->user_id];

        foreach($booking->users as $user) {
            $users[] = $user->id;
        }

        return User::whereNotIn('id', $users)->orderBy('name')->get();
    }

}