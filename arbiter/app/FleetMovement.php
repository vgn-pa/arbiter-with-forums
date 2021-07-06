<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetMovement extends Model
{
    use SoftDeletes;

    protected $table = 'fleet_movements';

    protected $fillable = [
      'launch_tick',
      'fleet_name',
      'planet_from_id',
      'planet_to_id',
      'mission_type',
      'land_tick',
      'tick',
      'eta',
      'ship_count',
      'source'
    ];

    public function planetTo()
    {
        return $this->hasOne(Planet::class, 'id', 'planet_to_id');
    }

    public function planetFrom()
    {
        return $this->hasOne(Planet::class, 'id', 'planet_from_id');
    }
}
