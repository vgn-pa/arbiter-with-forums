<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Tick extends Model
{
    protected $table = 'ticks';

    protected $fillable = [
        'tick',
        'time',
        'length'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("D d/m H:00");
    }
}
