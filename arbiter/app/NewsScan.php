<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsScan extends Model
{
    protected $table = 'news_scans';

    protected $fillable = [
        'scan_id'
    ];

    public function scan()
    {
        return $this->hasOne(Scan::class, 'id', 'scan_id');
    }
}
