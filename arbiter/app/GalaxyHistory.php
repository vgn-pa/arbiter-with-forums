<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GalaxyHistory extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'galaxy_history';

    protected $fillable = [
        'id',
        'x',
        'y',
        'galaxy_name',
        'size',
        'score',
        'value',
        'xp',
        'tick',
        'planet_count',
        'galaxy_id',
        'change_value',
        'change_score',
        'change_xp',
        'change_size',
        'change_planet_count',
        'rank_value',
        'rank_score',
        'rank_xp',
        'rank_size'
    ];

    public function getSizeAttribute($value)
    {
        return number_format($value);
    }

    public function getValueAttribute($value)
    {
        return number_format($value);
    }

    public function getScoreAttribute($value)
    {
        return number_format($value);
    }

    public function getXpAttribute($value)
    {
        return number_format($value);
    }

    public function tick()
    {
        return $this->hasOne(Tick::class, 'tick', 'tick');
    }
}
