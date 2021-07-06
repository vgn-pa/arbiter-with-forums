<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\PlanetScan;
use Config;

class ScanQueue extends Model
{
    use SoftDeletes;

    protected $table = 'scan_queue';

    protected $appends = ['scanUrl'];

    protected $fillable = [
        'scan_id',
        'processed'
    ];

    public function getScanUrlAttribute()
    {
        return Config::get('pa.scan_url') . $this->scan_id;
    }
}
