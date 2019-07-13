<?php
namespace common\models;

use common\models\base\{AClub, AGuest};
use common\models\data\Guest;
use common\models\interfaces\{IGuestBehavior};

class RussianGuest extends AGuest implements IGuestBehavior
{
    const NATIONAL_CODE = 'RUS';

    public function musicSetting(Music $music): void
    {
        foreach ($music->getAllGenres() as $genre => $nationality) {
            if (in_array(self::class, $nationality) && mt_rand(0, 100) <= 75) {
                $this->fullGenres = array_merge($this->fullGenres, [$genre => $nationality]);
            } else if (mt_rand(0, 100) <= 25) {
                $this->fullGenres = array_merge($this->fullGenres, [$genre => $nationality]);
            }
        }

        if (!count($this->fullGenres)) {
            $this->fullGenres = array_merge($this->fullGenres, $music->getRandomGenre());
        }
    }

    public function drinkSetting(Drink $drink): void
    {
        $this->fullKinds = array_merge($this->fullKinds, $drink->getRandomKind());
    }

    /**
     * @param AClub $club
     * @param Guest $guest
     * @return AGuest
     * @throws \RedisException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function create(AClub $club, Guest $guest): AGuest
    {
        $container = new \yii\di\Container;
        $container->set('common\models\interfaces\IPlaylist', 'common\models\data\Playlist');
        $container->get('common\models\Music');
        $container->set('common\models\interfaces\IAssortment', 'common\models\data\Assortment');
        $container->get('common\models\Drink');

        $container->invoke([$this, 'musicSetting']);
        $container->invoke([$this, 'drinkSetting']);
        $container->invoke([$this, 'moodSetting'], ['kinds' => $club->getKinds(), 'genre' => $club->getPlayGenre()]);

        // maybe different options

        $guest->attributes = $this->toSave();
        if (!$guest->validate() || !$guest->save()) {
            throw new \RedisException('Error save guest');
        }

        return $this;
    }

    /**
     * @param Drink $drink
     * @param array $kinds
     * @param string $genre
     * @throws \yii\base\InvalidConfigException
     */
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