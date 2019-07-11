<?php
namespace common\interfaces;

use common\models\Drink;

interface IGuestBehavior
{
    public function setMood(Drink $drink, array $kinds, string $genre): void;
}