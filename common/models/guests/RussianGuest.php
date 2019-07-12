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
                $this->genres = array_merge($this->genres, [$genre => $nationality]);
            } else if (mt_rand(0, 100) <= 25) {
                $this->genres = array_merge($this->genres, [$genre => $nationality]);
            }
        }

        if (!count($this->genres)) {
            $this->genres = array_merge($this->genres, $music->getRandomGenre());
        }
    }

    public function drinkSetting(Drink $drink): void
    {
        $this->kinds = array_merge($this->kinds, $drink->getRandomKind());
    }

    public function moodSetting(Drink $drink, array $kinds, string $genre): void
    {
        if (in_array($genre, array_keys($this->getGenres()))) {
            $this->setMood(self::STATUS_DANCE);
        } elseif (count(array_intersect_key($this->getKinds(), $kinds))) {
            $this->setMood(self::STATUS_DRUNK);
        } elseif (count(array_intersect_key($this->getKinds(), $drink->getAllKinds()))) { // ;-)
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