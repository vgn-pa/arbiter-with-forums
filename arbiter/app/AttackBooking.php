<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttackBooking extends Model
{
    protected $table = 'attack_bookings';

    protected $fillable = [
        'attack_id',
        'attack_target_id',
        'land_tick',
        'user_id',
        'notes',
        'battle_calc'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function attack()
    {
        return $this->hasOne(Attack::class);
    }

    public function target()
    {
        return $this->hasOne(AttackTarget::class, 'id', 'attack_target_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'attack_booking_users', 'booking_id', 'user_id');
    }
}
