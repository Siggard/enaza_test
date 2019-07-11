<?php
namespace common\interfaces;

use common\models\Music;
use common\models\Drink;

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
}