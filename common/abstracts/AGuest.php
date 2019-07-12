<?php
namespace common\abstracts;

use common\models\{Drink, Music};

abstract class AGuest
{
    const NATIONAL_CODE = 'UFO';

    const STATUS_DANCE = 0;
    const STATUS_DRUNK = 1;
    const STATUS_AWAY = 2;

    protected $genres = [], $kinds = [];
    protected $mood, $id;

    abstract function sayHello(): string;

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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }



    public function getGenres(): array
    {
        return $this->genres;
    }

    public function getKinds(): array
    {
        return $this->kinds;
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
            'status' => $this->getMood(),
            'national' => static::NATIONAL_CODE,
            'genres' => implode(',', array_keys($this->getGenres())),
            'kinds' => implode(',', array_keys($this->getKinds()))
        ];
    }
}