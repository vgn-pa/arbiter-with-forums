<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevelopmentScan extends Model
{
    protected $table = 'development_scans';

    protected $fillable = [
        'scan_id',
        'light_factory',
        'medium_factory',
        'heavy_factory',
        'wave_amplifier',
        'wave_distorter',
        'metal_refinery',
        'crystal_refinery',
        'eonium_refinery',
        'research_lab',
        'finance_centre',
        'security_centre',
        'travel',
        'infrastructure',
        'hulls',
        'waves',
        'core',
        'covert_op',
        'mining',
        'population',
        'military_centre',
        'structure_defence'
    ];

    public function scan()
    {
        return $this->hasOne(Scan::class, 'id', 'scan_id');
    }
}
