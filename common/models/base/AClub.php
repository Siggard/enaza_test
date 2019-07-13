<?php
namespace common\models\base;

use common\models\{
    data\Club, Drink, interfaces\IClubCreate, Music
};
use yii\base\Model;

abstract class AClub extends Model implements IClubCreate
{
    const SONG_TIME = 30;

    protected $playGenre, $playTime, $genres, $kinds, $id;
    protected $fullGenres = [], $fullKinds = [];

    abstract function playRandomMusic(): void;
    abstract function checkNextMusic(Club $club): array;

    /**
     * @param Music $music
     */
    public function setFullGenres(Music $music): void
    {
        $tmpGenres = explode(',', $this->genres);
        $this->fullGenres = array_intersect_key($music->getAllGenres(), array_fill_keys(array_map('trim', $tmpGenres), ''));
    }

    /**
     * @param Drink $drink
     */
    public function setFullKinds(Drink $drink): void
    {
        $tmpKinds = explode(',', $this->kinds);
        $this->fullKinds = array_intersect_key($drink->getAllKinds(), array_fill_keys(array_map('trim', $tmpKinds), ''));
    }

    /**
     * @param $genres
     */
    public function setGenres($genres)
    {
        $this->genres = $genres;
    }

    /**
     * @param $kinds
     */
    public function setKinds($kinds)
    {
        $this->kinds = $kinds;
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

    /**
     * @param string $genre
     */
    public function setPlayMusic(string $genre): void
    {
        $this->setPlayGenre($genre);
        $this->setPlayTime(strtotime('now'));
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
        return $this->fullKinds;
    }

    public function getGenres(): array
    {
        return $this->fullGenres;
    }



    public function toSave(): array
    {
        return [
            'playGenre' => $this->getPlayGenre(),
            'playTime' => $this->getPlayTime(),
            'genres' => implode(',', array_keys($this->getGenres())),
            'kinds' => implode(',', array_keys($this->getKinds()))
        ];
    }
}