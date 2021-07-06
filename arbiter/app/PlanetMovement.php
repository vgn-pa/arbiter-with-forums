<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanetMovement extends Model
{
    protected $table = 'planet_movements';

    protected $fillable = [
      'planet_id',
      'from_x',
      'from_y',
      'from_z',
      'to_x',
      'to_y',
      'to_z',
      'tick',
    ];

    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }
}
