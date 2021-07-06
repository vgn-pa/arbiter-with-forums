<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PlanetScan;

class IntelChange extends Model
{
    protected $table = 'intel_change';

    protected $fillable = [
        'planet_id',
        'alliance_from_id',
        'alliance_to_id',
        'tick'
    ];

    public function planet()
    {
        return $this->hasOne(Planet::class, 'id', 'planet_id');
    }

    public function allianceFrom()
    {
        return $this->hasOne(Alliance::class, 'id', 'alliance_from_id');
    }

    public function allianceTo()
    {
        return $this->hasOne(Alliance::class, 'id', 'alliance_to_id');
    }
}
