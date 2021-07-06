<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Planet extends Model
{
    use SoftDeletes;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'planets';
    protected $appends = [
      'basher',
      'is_alliance',
      'is_protected',
      'coords'
    ];

    protected $fillable = [
        'planet_id',
        'nick',
        'alliance_id',
        'galaxy_id',
        'latest_p',
        'latest_d',
        'latest_u',
        'latest_j',
        'latest_au',
        'amps',
        'dists',
        'waves',
        'min_alert',
        'max_alert',
        'total_cons',
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
        'covop_hit',
        'rank_size',
        'rank_value',
        'rank_score',
        'rank_xp',
        'day_rank_size',
        'day_rank_value',
        'day_rank_score',
        'day_rank_xp',
        'day_size',
        'day_value',
        'day_score',
        'day_xp',
        'growth_value',
        'growth_score',
        'growth_size',
        'growth_xp',
        'growth_rank_value',
        'growth_rank_score',
        'growth_rank_size',
        'growth_rank_xp',
        'growth_percentage_value',
        'growth_percentage_score',
        'growth_percentage_size',
        'growth_percentage_xp',
        'last_covopped',
        'age'
    ];

    public function __toString()
    {
        $planet = sprintf("%s:%s:%s (%s) '%s' of '%s' ", $this->x, $this->y, $this->z, $this->race, $this->ruler_name, $this->planet_name);
        $planet .= sprintf("Score: %s (%s) ", number_shorten($this->score, 1), $this->rank_score);
        $planet .= sprintf("Value: %s (%s) ", number_shorten($this->value, 1), $this->rank_value);
        $planet .= sprintf("Size: %s (%s) ", number_shorten($this->size, 1), $this->rank_size);
        $planet .= sprintf("Xp: %s (%s) ", number_shorten($this->xp, 1), $this->rank_xp);

        return $planet;
    }

    public function getIsProtectedAttribute()
    {
        if($this->age < 24) {
            return true;
        }

        return false;
    }

    public function getBasherAttribute()
    {
        if(!Auth::user() || !Auth::user()->planet_id) return false;

        $user = User::with('planet')->find(Auth::user()->id);
        $bashScore = $user->planet->getOriginal('score') * 0.6;
        $bashValue = $user->planet->getOriginal('value') * 0.4;
        if($this->getOriginal('score') < $bashScore && $this->getOriginal('value') < $bashValue) return true;

        return false;
    }

    public function getCoordsAttribute()
    {
        return $this->x . ":" . $this->y . ":" . $this->z;
    }

    public function getWavesAttribute($value)
    {
        $waves = [
            '0' => 'P',
            '1' => 'L',
            '2' => 'D',
            '3' => 'U',
            '4' => 'N',
            '5' => 'I',
            '6' => 'J',
            '7' => 'A'
        ];

        return $waves[$value];
    }

    public function getIsAllianceAttribute($value)
    {
        $settings = Setting::all()->keyBy('name')->toArray();
        if($this->alliance && $this->alliance->id == $settings['alliance']['value']) {
            return true;
        } else {
            return false;
        }
    }

    public function currentPlanet()
    {
      return $this->hasOne(PlanetHistory::class)->latest();
    }

    public function history()
    {
        return $this->hasMany(PlanetHistory::class, 'planet_id');
    }

    public function lastTick()
    {
        return $this->hasOne(PlanetHistory::class, 'planet_id')->orderBy('tick', 'DESC')->limit(1);
    }

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    public function latestP()
    {
        return $this->hasOne(PlanetScan::class, 'id', 'latest_p');
    }

    public function latestD()
    {
        return $this->hasOne(DevelopmentScan::class, 'id', 'latest_d');
    }

    public function latestU()
    {
        return $this->hasOne(Scan::class, 'id', 'latest_u');
    }

    public function latestA()
    {
        return $this->hasOne(Scan::class, 'id', 'latest_au');
    }

    public function latestN()
    {
        return $this->hasOne(NewsScan::class, 'id', 'latest_n');
    }

    public function latestJ()
    {
        return $this->hasOne(JgpScan::class, 'id', 'latest_j');
    }

    public function galaxy()
    {
        return $this->hasOne(Galaxy::class, 'id', 'galaxy_id');
    }

    public function outgoing()
    {
        return $this->hasMany(FleetMovement::class, 'planet_from_id');
    }

    public function incoming()
    {
        return $this->hasMany(FleetMovement::class, 'planet_to_id');
    }

}
