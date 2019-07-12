<?php
namespace common\models;

use common\interfaces\IPlaylist;

class Music
{
    private $_genres = [];

    public function __construct(IPlaylist $playlist)
    {
        $this->_genres = $playlist->create();

        if (empty($this->_genres)) {
            throw new \yii\base\InvalidConfigException('Genres is empty');
        }
    }

    public function getRandomGenre(): array
    {
        $key = array_rand($this->_genres, 1);
        return [$key => $this->_genres[$key]];
    }

    public function getAllGenres(): array
    {
        return $this->_genres;
    }
}