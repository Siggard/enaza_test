<?php
namespace common\models\guests;

use common\interfaces\{IGuestBehavior, IGuestCreate};
use common\abstracts\AGuest;
use common\models\{Drink, Music};

class GermanGuest extends AGuest implements IGuestCreate, IGuestBehavior
{
    const NATIONAL_CODE = 'GER';

    public function musicSetting(Music $music): void
    {
        array_push($this->genres, $music->getRandomGenre());
    }

    public function drinkSetting(Drink $drink): void
    {
        array_push($this->kinds, $drink->getRandomKind());
    }

    public function moodSetting(Drink $drink, array $kinds, string $genre): void
    {
        if (in_array($genre, $this->getGenres())) {
            $this->setMood(self::STATUS_DANCE);
        } elseif (count(array_intersect($this->getKinds(), $kinds))) {
            $this->setMood(self::STATUS_DRUNK);
        } else {
            $this->setMood(self::STATUS_AWAY);
        }
    }

    public function sayHello(): string
    {
        return 'I am ' . self::NATIONAL_CODE . ' => Guten Tag! => ' . self::getStatusName()[$this->mood];
    }
}