<?php
namespace common\models\factories;

use common\models\base\AClub;
use common\models\data\Club;
use common\models\{Drink, Music, NightClub};

class ClubFactory
{
    /**
     * @param Club $props
     * @param Music $music
     * @param Drink $drink
     * @return AClub
     */
    public static function makeClub(Club $props, Music $music, Drink $drink): AClub
    {
        $club = new NightClub();

        $club->setGenres($props->genres);
        $club->setFullGenres($music);
        $club->setKinds($props->kinds);
        $club->setFullKinds($drink);
        $club->setPlayGenre($props->playGenre);
        $club->setPlayTime($props->playTime);
        $club->setId($props->id);

        return $club;
    }
}