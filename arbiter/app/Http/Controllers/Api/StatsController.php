<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Planet;
use App\Galaxy;
use App\Alliance;

class StatsController extends ApiController
{
    public function index()
    {
        return [
            'planets' => [
                'roided' => Planet::orderBy('growth_size', 'ASC')->limit(5)->get(),
                'roiding' => Planet::orderBy('growth_size', 'DESC')->limit(5)->get(),
                'score_gain' => Planet::orderBy('growth_score', 'DESC')->limit(5)->get(),
                'value_gain' => Planet::orderBy('growth_value', 'DESC')->limit(5)->get(),
                'value_loss' => Planet::orderBy('growth_value', 'ASC')->limit(5)->get()
            ],
            'galaxies' => [
              'roided' => Galaxy::orderBy('growth_size', 'ASC')->limit(5)->get(),
              'roiding' => Galaxy::orderBy('growth_size', 'DESC')->limit(5)->get(),
              'score_gain' => Galaxy::orderBy('growth_score', 'DESC')->limit(5)->get(),
              'value_gain' => Galaxy::orderBy('growth_value', 'DESC')->limit(5)->get(),
              'value_loss' => Galaxy::orderBy('growth_value', 'ASC')->limit(5)->get()
            ],
            'alliances' => [
              'roided' => Alliance::orderBy('growth_size', 'ASC')->limit(5)->get(),
              'roiding' => Alliance::orderBy('growth_size', 'DESC')->limit(5)->get(),
              'score_gain' => Alliance::orderBy('growth_score', 'DESC')->limit(5)->get(),
              'value_gain' => Alliance::orderBy('growth_value', 'DESC')->limit(5)->get(),
              'value_loss' => Alliance::orderBy('growth_value', 'ASC')->limit(5)->get()
            ]
        ];
    }
}
