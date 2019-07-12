<?php
namespace common\factorys;

use common\abstracts\AGuest;
use common\models\data\Guest;
use common\models\{Drink, Music};

class GuestFactory
{
    public static function makeGuest(Guest $props, Music $music, Drink $drink): AGuest
    {
        $guestNational = \Yii::$app->params['nationals'][$props->national];

        /**
         * @var $guest \common\interfaces\IGuestCreate | \common\abstracts\AGuest | \common\interfaces\IGuestBehavior
         */
        $guest = new $guestNational();
        $guest->setMood((int)$props->status);
        $guest->setGenres($props->genres, $music);
        $guest->setKinds($props->kinds, $drink);
        $guest->setId($props->id);

        return $guest;
    }
}