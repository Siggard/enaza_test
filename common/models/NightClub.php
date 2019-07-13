<?php
namespace common\models;

use common\models\base\AClub;
use common\models\base\AGuest;
use common\models\data\Club;
use common\models\factories\GuestFactory;
use common\models\interfaces\{IPlaylist, IAssortment};
use yii\redis\ActiveRecord;

class NightClub extends AClub
{
    /**
     * @param IPlaylist $playlist
     * @throws \yii\base\InvalidConfigException
     */
    public function musicSetting(IPlaylist $playlist): void
    {
        $this->fullGenres = $playlist->create();

        if (empty($this->fullGenres)) {
            throw new \yii\base\InvalidConfigException('Genres is empty');
        }
    }

    /**
     * @param IAssortment $assortment
     * @throws \yii\base\InvalidConfigException
     */
    public function drinkSetting(IAssortment $assortment): void
    {
        $this->fullKinds = $assortment->create();

        if (empty($this->fullKinds)) {
            throw new \yii\base\InvalidConfigException('Kinds is empty');
        }
    }

    /**
     * @param Club $club
     * @return $this|mixed
     * @throws \RedisException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function create(Club $club)
    {
        $container = new \yii\di\Container;
        $container->set('common\models\interfaces\IPlaylist', 'common\models\data\Playlist');
        $container->get('common\models\Music');
        $container->set('common\models\interfaces\IAssortment', 'common\models\data\Assortment');
        $container->get('common\models\Drink');

        $container->invoke([$this, 'musicSetting']);
        $container->invoke([$this, 'drinkSetting']);

        $this->hideKind();
        $this->playRandomMusic();

        $club->attributes = $this->toSave();
        if (!$club->validate() || !$club->save()) {
            throw new \RedisException('Error save club');
        }

        return $this;
    }

    /**
     * @param Club $oldClub
     * @return $this|mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function loadSingle(Club $oldClub)
    {
        $this->playGenre = $oldClub->playGenre;
        $this->playTime = $oldClub->playTime;
        $this->genres = $oldClub->genres;
        $this->kinds = $oldClub->kinds;

        $container = new \yii\di\Container;
        $container->set('common\models\interfaces\IPlaylist', 'common\models\data\Playlist');
        $container->get('common\models\Music');
        $container->set('common\models\interfaces\IAssortment', 'common\models\data\Assortment');
        $container->get('common\models\Drink');

        $container->invoke([$this, 'setFullGenres']);
        $container->invoke([$this, 'setFullKinds']);

        return $this;
    }

    /**
     * add little random
     */
    private function hideKind(): void
    {
        $key = array_rand($this->fullKinds, 1);
        unset($this->fullKinds[$key]);
    }

    public function playRandomMusic(): void
    {
        $genres = $this->fullGenres;
        if ($this->playGenre) {
            unset($genres[$this->playGenre]);
        }

        $genres = array_keys($genres);
        $this->playGenre = $genres[array_rand($genres, 1)];
        $this->playTime = strtotime('now');
    }

    /**
     * best genre for our guests
     * @param $guests
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function topGenre($guests): string
    {
        $container = new \yii\di\Container;

        /**
         * @var $music \common\models\Music
         */
        $container->set('common\models\interfaces\IPlaylist', 'common\models\data\Playlist');
        $music = $container->get('common\models\Music');

        /**
         * @var $drink \common\models\Drink
         */
        $container->set('common\models\interfaces\IAssortment', 'common\models\data\Assortment');
        $drink = $container->get('common\models\Drink');

        $genres = array_fill_keys(array_keys($music->getAllGenres()), 0);

        /**
         * @var $guest AGuest
         */
        foreach ($guests as $rGuest) {
            $guest = GuestFactory::makeGuest($rGuest, $music, $drink);

            $container->invoke([$guest, 'setFullGenres']);
            $container->invoke([$guest, 'setFullKinds']);

            foreach(array_keys($guest->getGenres()) as $genre) {
                $genres[$genre]++;
            }
        }

        arsort($genres);

        return array_shift(array_keys($genres));
    }
}