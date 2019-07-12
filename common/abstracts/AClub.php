<?php
namespace common\abstracts;

use common\models\{Drink, Music};

abstract class AClub
{
    const SONG_TIME = 120;

    protected $genres = [], $kinds = [];
    protected $playGenre, $playTime, $id;

    abstract function hideKind(): void;
    abstract function playRandomMusic(): void;

    /**
     * @param string $genres
     * @param Music $music
     */
    public function setGenres(string $genres, Music $music): void
    {
        $tmpGenres = explode(',', $genres);
        $this->genres = array_intersect_key($music->getAllGenres(), array_fill_keys(array_map('trim', $tmpGenres), ''));
    }

    /**
     * @param string $kinds
     * @param Drink $drink
     */
    public function setKinds(string $kinds, Drink $drink): void
    {
        $tmpKinds = explode(',', $kinds);
        $this->kinds = array_intersect_key($drink->getAllKinds(), array_fill_keys(array_map('trim', $tmpKinds), ''));
    }

    /**
     * @param string $genre
     */
    public function setPlayGenre(string $genre): void
    {
        $this->playGenre = $genre;
    }

    /**
     * @param int $time
     */
    public function setPlayTime(int $time): void
    {
        $this->playTime = $time;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }



    public function getPlayGenre(): string
    {
        return $this->playGenre;
    }

    public function getPlayTime(): int
    {
        return $this->playTime;
    }

    public function getLeftMusicTime(): int
    {
        $ago = self::SONG_TIME - (strtotime('now') - $this->playTime);
        if ($ago < 0) {
            $ago = 0;
        }

        return $ago;
    }

    public function getKinds(): array
    {
        return $this->kinds;
    }

    public function getGenres(): array
    {
        return $this->genres;
    }



    public function toSave(): array
    {
        return [
            'play' => $this->getPlayGenre(),
            'time' => $this->getPlayTime(),
            'genres' => implode(',', array_keys($this->getGenres())),
            'kinds' => implode(',', array_keys($this->getKinds()))
        ];
    }
}