<?php
namespace common\models\guests;

use common\interfaces\IGuestBehavior;
use common\interfaces\IGuestCreate;
use common\abstracts\AGuest;
use common\models\Drink;
use common\models\Music;

class RussianGuest extends AGuest implements IGuestCreate, IGuestBehavior
{
    const NATIONAL_CODE = 'RUS';

    public function musicSetting(Music $music): void
    {
        foreach ($music->getAllGenres() as $genre => $nationality) {
            if (in_array(self::class, $nationality) && mt_rand(0, 100) <= 75) {
                array_push($this->genres, $genre);
            } else if (mt_rand(0, 100) <= 25) {
                array_push($this->genres, $genre);
            }
        }

        if (!count($this->genres)) {
            array_push($this->genres, $music->getRandomGenre());
        }
    }

    public function drinkSetting(Drink $drink): void
    {
        array_push($this->drinks, $drink->getRandomKind());
    }

    public function setMood(Drink $drink, array $kinds, string $genre): void
    {
        if (in_array($genre, $this->getMusic())) {
            $this->mood = self::STATUS_DANCE;
        } elseif (array_intersect($this->getDrinks(), $kinds)) {
            $this->mood = self::STATUS_DRUNK;
        } elseif (array_intersect($this->getDrinks(), $drink->getAllKinds())) { // ;-)
            $this->mood = self::STATUS_DRUNK;
        } else {
            $this->mood = self::STATUS_AWAY;
        }
    }

    public function sayHello(): string
    {
        return 'I am ' . self::NATIONAL_CODE . ' => Добрый день! => ' . self::getStatusName()[$this->mood];
    }
}