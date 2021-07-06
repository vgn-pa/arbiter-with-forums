<?php

namespace App\Services;

use App\Galaxy;

class ParseCoordsList {

    private $coords;
    private $targets = [];

    public function setCoords($coords)
    {
        $this->coords = $coords;

        return $this;
    }

    public function execute()
    {
        $this->parsePlanets();
        $this->parseGalaxies();
        $this->sortTargets();

        return $this->targets;
    }

    private function parsePlanets()
    {
        preg_match_all("/(\d+)[:.](\d+)[:.](\d+)/", $this->coords, $planets);

        foreach($planets[1] as $key => $target) {
            $this->targets[$key]['x'] = $target;
        }

        foreach($planets[2] as $key => $target) {
            $this->targets[$key]['y'] = $target;
        }

        foreach($planets[3] as $key => $target) {
            $this->targets[$key]['z'] = $target;
        }
    }

    private function parseGalaxies()
    {
        preg_match_all("/(\d+)[:.](\d+)[:.](\*)/", $this->coords, $gals);

        $galCoords = [];

        foreach($gals[1] as $key => $target) {
            $galCoords[$key]['x'] = $target;
        }

        foreach($gals[2] as $key => $target) {
            $galCoords[$key]['y'] = $target;
        }

        foreach($galCoords as $gal) {
            $galaxy = Galaxy::with('planets')->where(['x' => $gal['x'], 'y' => $gal['y']])->first();

            foreach($galaxy->planets as $planet) {
                $this->targets[] = [
                    'x' => $planet->x,
                    'y' => $planet->y,
                    'z' => $planet->z
                ];
            }
        }

        
    }

    private function sortTargets()
    {
        foreach ($this->targets as $key => $row) {
            $x[$key] = $row['x'];
            $y[$key] = $row['y'];
            $z[$key] = $row['z'];
        }

        if($this->targets) {
            array_multisort($x, SORT_ASC, $y, SORT_ASC, $z, SORT_ASC, $this->targets);  
        }
    }
 }
