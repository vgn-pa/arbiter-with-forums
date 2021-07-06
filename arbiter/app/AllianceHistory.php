<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllianceHistory extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'alliance_history';

    protected $fillable = [
        'rank',
        'alliance_id',
        'name',
        'size',
        'members',
        'counted_score',
        'points',
        'total_score',
        'total_value',
        'tick',
        'avg_size',
        'avg_value',
        'avg_score',
        'change_value',
        'change_score',
        'change_size',
        'rank_value',
        'rank_score',
        'rank_size',
        'rank_avg_value',
        'rank_avg_score',
        'rank_avg_size',
        'change_members'
    ];

    public function getSizeAttribute($value)
    {
        return number_format($value);
    }

    public function getTotalValueAttribute($value)
    {
        return number_format($value);
    }

    public function getCountedScoreAttribute($value)
    {
        return number_format($value);
    }

    public function getAvgSizeAttribute($value)
    {
        return number_format($value);
    }

    public function getAvgValueAttribute($value)
    {
        return number_format($value);
    }

    public function getAvgScoreAttribute($value)
    {
        return number_format($value);
    }

    public function alliance()
    {
        return $this->hasOne(Alliance::class, 'id', 'alliance_id');
    }

    public function tick()
    {
        return $this->hasOne(Tick::class, 'tick', 'tick');
    }
}
