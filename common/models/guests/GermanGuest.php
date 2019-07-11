<?php
namespace common\models\guests;

use common\interfaces\IGuestBehavior;
use common\interfaces\IGuestCreate;
use common\abstracts\AGuest;
use common\models\Drink;
use common\models\Music;

class GermanGuest extends AGuest implements IGuestCreate, IGuestBehavior
{
    const NATIONAL_CODE = 'GER';

    public function musicSetting(Music $music): void
    {
        array_push($this->genres, $music->getRandomGenre());
    }

    public function drinkSetting(Drink $drink): void
    {
        array_push($this->drinks, $drink->getRandomKind());
    }

    public function setMood(Drink $drink, array $kinds, string $genre): void
    {
        if (in_array($genre, $this->getMusic())) {
            $this->mood = self::STATUS_DANCE;
        } elseif (count(array_intersect($this->getDrinks(), $kinds))) {
            $this->mood = self::STATUS_DRUNK;
        } else {
            $this->mood = self::STATUS_AWAY;
        }
    }

    public function sayHello(): string
    {
        return 'I am ' . self::NATIONAL_CODE . ' => Guten Tag! => ' . self::getStatusName()[$this->mood];
    }
}