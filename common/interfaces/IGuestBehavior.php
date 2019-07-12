<?php
namespace common\interfaces;

use common\models\Drink;

interface IGuestBehavior
{
    public function moodSetting(Drink $drink, array $kinds, string $genre): void;
}