<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Galaxy extends Model
{
  /**
  * The table associated with the model.
  *
  * @var string
  */
  protected $table = 'galaxies';

  protected $appends = [
      //'has_members'
  ];

  protected $fillable = [
      'x',
      'y',
      'hidden_resources',
      'name',
      'rank_size',
      'rank_value',
      'rank_score',
      'rank_xp',
      'ratio',
      'planet_count'
  ];

  public function gethasMembersAttribute($value)
    {
        $settings = Setting::all()->keyBy('name')->toArray();
        $allianceId = $settings['alliance']['value'];

        if($this->planets->where('alliance_id', $allianceId)->count()) {
            return true;
        } else {
            return false;
        }
    }

  public function getAlliancesAttribute()
  {
      $settings = Setting::all()->keyBy('name')->toArray();

      $ally = [];

      $planets = $this->planets()->get();

      foreach($planets as $planet) {
          if($planet->alliance) {
              $name = $planet->alliance->nickname;
              if(isset($ally[$planet->alliance->name])) {
                  $ally[$planet->alliance->name]['count']++;
              } else {
                  $ally[$planet->alliance->name]['count'] = 1;
                  $ally[$planet->alliance->name]['nickname'] = $name;
                  $ally[$planet->alliance->name]['name'] = $planet->alliance->name;
                  $ally[$planet->alliance->name]['relation'] = $planet->alliance->relation;
                  $ally[$planet->alliance->name]['is_alliance'] = ($planet->alliance->id == $settings['alliance']['value']) ? true : false;
              }
          }
      }

      ksort($ally);

      return $ally;
  }

  public function getRacesAttribute()
  {
      $races = [
          'Ter' => 0,
          'Cat' => 0,
          'Xan' => 0,
          'Zik' => 0,
          'Etd' => 0
      ];

      $planets = $this->planets()->get();


      foreach($planets as $planet) {
          $races[$planet->race]++;
      }

      return $races;
  }

//   public function getHasMembersAttribute()
//   {
//       $settings = Setting::all()->keyBy('name')->toArray();

//       $planets = $this->planets()->get();

//       foreach($planets as $planet) {
//           if($planet->alliance && $planet->alliance->id == $settings['alliance']['value']) {
//               return true;
//           }
//       }

//       return false;
//   }

  public function currentGalaxy()
  {
    return $this->hasOne(GalaxyHistory::class)->latest();
  }

  public function history()
  {
      return $this->hasMany(GalaxyHistory::class, 'galaxy_id');
  }

  public function planets()
  {
      return $this->hasMany(Planet::class, 'galaxy_id', 'id')->orderBy('z', 'ASC');
  }
}
