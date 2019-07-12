<?php
namespace common\factorys;

use common\abstracts\AClub;
use common\models\data\Club;
use common\models\{Drink, Music, NightClub};

class ClubFactory
{
    public static function makeClub(Club $props, Music $music, Drink $drink): AClub
    {
        $club = new NightClub();

        $club->setGenres($props->genres, $music);
        $club->setKinds($props->kinds, $drink);
        $club->setPlayGenre($props->play);
        $club->setPlayTime($props->time);
        $club->setId($props->id);

        return $club;
    }
}