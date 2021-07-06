<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitScan extends Model
{
    protected $table = 'unit_scans';

    protected $fillable = [
        'scan_id',
        'ship_id',
        'amount'
    ];

    public function scan()
    {
        return $this->hasOne(Scan::class, 'id', 'scan_id');
    }

    public function ship()
    {
        return $this->hasOne(Ship::class, 'id', 'ship_id');
    }
}
