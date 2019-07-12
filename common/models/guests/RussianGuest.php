<?php
namespace common\models\guests;

use common\interfaces\{IGuestBehavior, IGuestCreate};
use common\abstracts\AGuest;
use common\models\{Drink, Music};

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
        array_push($this->kinds, $drink->getRandomKind());
    }

    public function moodSetting(Drink $drink, array $kinds, string $genre): void
    {
        if (in_array($genre, $this->getGenres())) {
            $this->setMood(self::STATUS_DANCE);
        } elseif (array_intersect($this->getKinds(), $kinds)) {
            $this->setMood(self::STATUS_DRUNK);
        } elseif (array_intersect($this->getKinds(), array_keys($drink->getAllKinds()))) { // ;-)
            $this->setMood(self::STATUS_DRUNK);
        } else {
            $this->setMood(self::STATUS_AWAY);
        }
    }

    public function sayHello(): string
    {
        return 'I am ' . self::NATIONAL_CODE . ' => Добрый день! => ' . self::getStatusName()[$this->mood];
    }
}