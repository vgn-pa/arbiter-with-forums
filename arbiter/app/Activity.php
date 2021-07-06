<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Activity extends Model
{
    protected $table = "activity";

    protected $fillable = [
        'user_id',
        'url',
        'method',
        'user_agent',
        'ip_address',
        'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
