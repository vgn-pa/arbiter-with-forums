<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanetScan extends Model
{
    protected $table = 'planet_scans';

    protected $fillable = [
        'scan_id',
        'roid_metal',
        'roid_crystal',
        'roid_eonium',
        'res_metal',
        'res_crystal',
        'res_eonium',
        'factory_usage_light',
        'factory_usage_medium',
        'factory_usage_heavy',
        'prod_res',
        'agents',
        'guards'
    ];

    public function getFactoryUsageLightAttribute($value)
    {
        return substr($value, 0, 1);
    }

    public function getFactoryUsageMediumAttribute($value)
    {
        return substr($value, 0, 1);
    }

    public function getFactoryUsageHeavyAttribute($value)
    {
        return substr($value, 0, 1);
    }

    public function scan()
    {
        return $this->hasOne(Scan::class, 'id', 'scan_id');
    }
}
