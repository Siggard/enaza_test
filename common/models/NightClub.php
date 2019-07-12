<?php
namespace common\models;

use common\abstracts\AClub;
use common\interfaces\{
    IClubCreate, IPlaylist, IAssortment
};

class NightClub extends AClub implements IClubCreate
{
    public function musicSetting(IPlaylist $playlist): void
    {
        $this->genres = $playlist->create();

        if (empty($this->genres)) {
            throw new \yii\base\InvalidConfigException('Genres is empty');
        }
    }

    public function drinkSetting(IAssortment $assortment): void
    {
        $this->kinds = $assortment->create();

        if (empty($this->kinds)) {
            throw new \yii\base\InvalidConfigException('Kinds is empty');
        }
    }

    // add little random
    public function hideKind(): void
    {
        $key = array_rand($this->kinds, 1);
        unset($this->kinds[$key]);
    }

    public function playRandomMusic(): void
    {
        $genres = $this->genres;
        if ($this->playGenre) {
            unset($genres[$this->playGenre]);
        }

        $genres = array_keys($genres);
        $this->playGenre = $genres[array_rand($genres, 1)];
        $this->playTime = strtotime('now');
    }
}