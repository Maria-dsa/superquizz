<?php

namespace App\Model;

use PDO;
use App\Entity\Game;

class ResultManager extends AbstractManager
{
    public function answerIntoPercent($nbGoodAnswer, $nbQuestions)
    {
        return round($nbGoodAnswer / $nbQuestions  *  100);
    }
}
