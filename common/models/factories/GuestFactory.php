<?php
namespace common\models\factories;

use common\models\base\AGuest;
use common\models\data\Guest;
use common\models\{Drink, Music};

class GuestFactory
{
    /**
     * @param Guest $props
     * @param Music $music
     * @param Drink $drink
     * @return AGuest
     * @throws \yii\base\InvalidConfigException
     */
    public static function makeGuest(Guest $props, Music $music, Drink $drink): AGuest
    {
        $guestNational = \Yii::$app->params['nationals'][$props->national];

        /**
         * @var $guest \common\models\interfaces\IGuestCreate | \common\models\base\AGuest | \common\models\interfaces\IGuestBehavior
         */
        $guest = new $guestNational();
        $guest->setMood((int)$props->mood);
        $guest->setGenres($props->genres);
        $guest->setFullGenres($music);
        $guest->setKinds($props->kinds);
        $guest->setFullKinds($drink);
        $guest->setId($props->id);

        return $guest;
    }
}