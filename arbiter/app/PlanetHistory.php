<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanetHistory extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'planet_history';

    protected $fillable = [
        'planet_id',
        'x',
        'y',
        'z',
        'ruler_name',
        'planet_name',
        'race',
        'size',
        'score',
        'value',
        'xp',
        'tick',
        'change_value',
        'change_score',
        'change_xp',
        'change_size',
        'rank_value',
        'rank_score',
        'rank_xp',
        'rank_size'
    ];

    public function getXpAttribute($value)
    {
        return number_format($value);
    }

    public function planet()
    {
        return $this->hasOne(Planet::class, 'id', 'planet_id');
    }

    public function tick()
    {
        return $this->hasOne(Tick::class, 'tick', 'tick');
    }
}
