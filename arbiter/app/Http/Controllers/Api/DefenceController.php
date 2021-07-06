<?php
namespace App\Http\Controllers\Api;

use App\Services\Defence\DefenceList;

class DefenceController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DefenceList $defence)
    {
        $tick = $this->settings['tick'];
        $alliance = $this->settings['alliance'] ?? null;

        return $defence->setTick($tick)
            ->setAllianceId($alliance)
            ->execute();
    }
}
