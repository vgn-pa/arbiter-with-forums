<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PlanetScan;
use Config;

class Scan extends Model
{
    protected $table = 'scans';

    protected $appends = [
        'type',
        'link'
    ];

    protected $fillable = [
        'planet_id',
        'scan_id',
        'scan_type',
        'pa_id',
        'tick',
        'time'
    ];

    public function getTypeAttribute()
    {
        switch($this->scan_type) {
            case 'App\PlanetScan': $type = 'P';
                break;
            case 'App\DevelopmentScan': $type = 'D';
                break;
            case 'App\UnitScan': $type = 'U';
                break;
            case 'App\NewsScan': $type = 'N';
                break;
            case 'App\JgpScan': $type = 'J';
                break;
            case 'App\AdvancedUnitScan': $type = 'A';
                break;
        }

        return $type;
    }

    public function getLinkAttribute()
    {
        return Config::get('pa.scan_url') . $this->pa_id;
    }

    public function details()
    {
        return $this->morphTo();
    }

    public function au()
    {
        return $this->hasMany(AdvancedUnitScan::class, 'scan_id', 'id');
    }

    public function u()
    {
        return $this->hasMany(UnitScan::class, 'scan_id', 'id');
    }

    public function planet()
    {
        return $this->belongsTo(Planet::class, 'planet_id', 'id');
    }
}
