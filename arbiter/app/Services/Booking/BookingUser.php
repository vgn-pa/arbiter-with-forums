<?php

namespace App\Services\Booking;

use App\AttackBooking;

class BookingUser 
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

    public function add()
    {
        $booking = AttackBooking::find($this->id);

        $booking->users()->syncWithoutDetaching([$this->userId]);
    }

    public function delete()
    {
        $booking = AttackBooking::find($this->id);

        $booking->users()->detach([$this->userId]);
    }

}