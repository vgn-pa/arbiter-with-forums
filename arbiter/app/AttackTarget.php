<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\Misc\MakeBattleCalc;
use App;

class AttackTarget extends Model
{
    protected $table = 'attack_targets';

    protected $fillable = [
        'attack_id',
        'planet_id'
    ];

    public function planet()
    {
        return $this->hasOne(Planet::class, 'id', 'planet_id');
    }

    public function attack()
    {
        return $this->hasOne(Attack::class, 'id', 'attack_id');
    }

    public function bookings()
    {
        return $this->hasMany(AttackBooking::class, 'attack_target_id', 'id');
    }
}
