<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attack extends Model
{
    protected $table = 'attacks';

    protected $fillable = [
        'land_tick',
        'waves',
        'notes',
        'scheduled_time',
        'is_opened',
        'is_closed',
        'attack_id'
    ];

    public function targets()
    {
        return $this->hasMany(AttackTarget::class);
    }

    public function bookings()
    {
        return $this->hasMany(AttackBooking::class);
    }
}
