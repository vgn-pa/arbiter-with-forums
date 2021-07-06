<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Services\Misc\RoidCost;
use App;

class RoidcostCommand extends BaseCommand
{
    protected $command = "!roidcost";

    public function execute()
    {
        $eff = App::make(RoidCost::class);

        $string = explode(" ", $this->text);

        if(!isset($string[0]) || !isset($string[1]) || !isset($string[2])) return "usage: !roidcost <roids> <value_loss> <mining_bonus>";

        return $eff->setRoids($string[0])
                ->setCost($string[1])
                ->setBonus($string[2])
                ->execute();

        return "There are no open attacks";
    }
}