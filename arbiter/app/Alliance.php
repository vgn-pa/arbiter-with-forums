<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alliance extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'alliances';

    protected $fillable = [
        'name',
        'hidden_resources',
        'rank_size',
        'rank_value',
        'rank_score',
        'rank_avg_size',
        'rank_avg_value',
        'rank_avg_score',
        'value',
        'score',
        'size',
        'avg_value',
        'avg_score',
        'avg_size',
        'members',
        'day_rank_value',
        'day_rank_score',
        'day_rank_size',
        'day_rank_avg_value',
        'day_rank_avg_score',
        'day_rank_avg_size',
        'nickname',
        'relation'
    ];

    public function getNicknameAttribute($value)
    {
        if($value) {
            return $value;
        } else {
            return str_replace(" ", "", substr($this->name, 0, 3));
        }
    }

    public function currentAlliance()
    {
      return $this->hasOne(AllianceHistory::class)->latest();
    }

    public function history()
    {
        return $this->hasMany(AllianceHistory::class, 'galaxy_id');
    }

    public function planets()
    {
        return $this->hasMany(Planet::class, 'alliance_id', 'id');
    }
}
