<?php
namespace common\models\base;

use common\models\{
    Drink, interfaces\IGuestCreate, Music
};
use yii\base\Model;

abstract class AGuest extends Model implements IGuestCreate
{
    const NATIONAL_CODE = 'UFO';

    const STATUS_DANCE = 0;
    const STATUS_DRUNK = 1;
    const STATUS_AWAY = 2;

    protected $mood, $kinds, $genres, $national, $id;
    protected $fullGenres = [], $fullKinds = [];

    abstract function sayHello(): string;

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
     * @param int $mood
     * @throws \yii\base\InvalidConfigException
     */
    public function setMood(int $mood): void
    {
        if (in_array($mood, [self::STATUS_DANCE, self::STATUS_DRUNK, self::STATUS_AWAY])) {
            $this->mood = $mood;
        } else {
            throw new \yii\base\InvalidConfigException('Wrong mood');
        }
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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }



    public function getGenres(): array
    {
        return $this->fullGenres;
    }

    public function getKinds(): array
    {
        return $this->fullKinds;
    }

    public function getMood(): string
    {
        return $this->mood;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function getStatusName(): array
    {
        return [
            self::STATUS_DANCE => 'let\'s dance',
            self::STATUS_DRUNK => 'go drunk',
            self::STATUS_AWAY => 'i am go home'
        ];
    }



    public function toSave(): array
    {
        return [
            'mood' => $this->getMood(),
            'national' => static::NATIONAL_CODE,
            'genres' => implode(',', array_keys($this->getGenres())),
            'kinds' => implode(',', array_keys($this->getKinds()))
        ];
    }
}