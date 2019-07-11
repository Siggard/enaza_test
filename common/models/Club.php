<?php
namespace common\models;

use common\interfaces\IPlaylist;
use common\interfaces\IAssortment;

class Club
{
    private $_playGenre;
    private $_playTime;

    private $_genres = [], $_kinds = [];

    const SONG_TIME = 120;

    public function __construct(IPlaylist $playlist, IAssortment $assortment)
    {
        $this->_genres = $playlist->create();
        $this->_kinds = $assortment->create();

        $this->hideKind();

        if (empty($this->_kinds) || empty($this->_genres)) {
            throw new \yii\base\InvalidConfigException('Kinds or genres is empty');
        }
    }

    private function hideKind(): void
    {
        $key = array_rand($this->_kinds, 1);
        unset($this->_kinds[$key]);
    }

    public function playMusic(): void
    {
        $this->_playGenre = array_rand($this->_genres, 1);
        $this->_playTime = strtotime('now');
    }

    public function getMusic(): string
    {
        return $this->_playGenre;
    }

    public function getLeftMusicTime(): int
    {
        $ago = self::SONG_TIME - (strtotime('now') - $this->_playTime);
        if ($ago < 0) {
//            $this->playMusic();
            $ago = 0;
        }

        return $ago;
    }

    public function getKindDrinks(): array
    {
        return array_keys($this->_kinds);
    }
}