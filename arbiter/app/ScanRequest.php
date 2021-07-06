<?php

namespace App;

use Config;
use Illuminate\Database\Eloquent\Model;
use App\Planet;

class ScanRequest extends Model
{
  protected $table = 'scan_requests';

  protected $fillable = [
      'scan_type',
      'planet_id',
      'user_id',
      'tick',
      'scan_id'
  ];

  protected $appends = ['scan_type_id'];

  public function getScanTypeIdAttribute()
  {
    $scans = Config::get('scans');

    return $scans[$this->scan_type];
  }

  public function __toString()
  {
    $planet = $this->planet()->first();

    $scans = Config::get('scans');

    return 'https://game.planetarion.com/waves.pl?id=' . $scans[$this->scan_type] . '&x=' . $planet->x . '&y=' . $planet->y . '&z=' . $planet->z;
  }

  public function planet()
  {
      return $this->belongsTo(Planet::class, 'planet_id');
  }

  public function user()
  {
      return $this->belongsTo(User::class, 'user_id');
  }

  public function scan()
  {
      return $this->belongsTo(Scan::class, 'scan_id');
  }
}
