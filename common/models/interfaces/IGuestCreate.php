<?php
namespace common\models\interfaces;

use common\models\base\{AClub, AGuest};
use common\models\data\Guest;
use common\models\{Music, Drink};

interface IGuestCreate
{
    /**
     * favorite genres
     * @param Music $music
     */
    public function musicSetting(Music $music): void;

    /**
     * favorite kind of drinks
     * @param Drink $drink
     */
    public function drinkSetting(Drink $drink): void;

    /**
     * @param AClub $club
     * @param Guest $guest
     * @return AGuest
     */
    public function create(AClub $club, Guest $guest): AGuest;
}