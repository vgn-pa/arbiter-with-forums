<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Config;
use App\Planet;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'planet_id',
        'phone',
        'timezone',
        'notes',
        'distorters',
        'military_centres',
        'is_enabled',
        'is_new',
        'last_login',
        'role_id',
        'tg_username',
        'stealth'
    ];

    protected $appends = [
        'notification_email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getnotificationEmailAttribute($value)
    {
        if(Config::get('notifications.email_notifications.enabled')) {
            $email = Config::get('notifications.email_notifications.email_address');
            $email = str_replace("@yourdomain.tld", "", $email);
            return $email . "+" . $this->id . "@yourdomain.tld";
        }

        return;
    }

    public function scopeAdmin($query)
    {
        return $query->whereHas('role', function($q) {
            $q->where('name', 'Admin');
        });
    }

    public function scopeMember($query)
    {
        return $query->whereHas('role', function($q) {
            $q->where('name', 'Member');
        });
    }

    public function scopeBc($query)
    {
        return $query->whereHas('role', function($q) {
            $q->where('name', 'BC');
        });
    }

    public function planet()
    {
        return $this->hasOne(Planet::class, 'id', 'planet_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id', 'id');
    }

    public function battlegroups()
    {
        return $this->hasMany(Battlegroup::class, 'battlegroup_members', 'user_id', 'battlegroup_id');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function bookings()
    {
        return $this->hasMany(AttackBooking::class);
    }

    public function sharedBookings()
    {
        return $this->belongsToMany(AttackBooking::class, 'attack_booking_user', 'user_id', 'booking_id');
    }
}
