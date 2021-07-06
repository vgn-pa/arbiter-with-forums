<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    protected $fillable = [
      'name',
      'class',
      't1',
      't2',
      't3',
      'type',
      'init',
      'guns',
      'armor',
      'damage',
      'empres',
      'metal',
      'crystal',
      'eonium',
      'total_cost',
      'race',
      'eta'
    ];
}
