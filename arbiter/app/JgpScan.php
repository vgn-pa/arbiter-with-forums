<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JgpScan extends Model
{
    protected $table = 'jgp_scans';

    protected $fillable = [
        'scan_id'
    ];

    public function scan()
    {
        return $this->hasOne(Scan::class, 'id', 'scan_id');
    }
}
