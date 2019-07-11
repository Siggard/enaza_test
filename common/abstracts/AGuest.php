<?php
namespace common\abstracts;

abstract class AGuest
{
    const NATIONAL_CODE = 'UFO';

    protected $genres = [];
    protected $drinks = [];

    const STATUS_DANCE = 0;
    const STATUS_DRUNK = 1;
    const STATUS_AWAY = 2;

    protected $mood;

    public function getMusic(): array
    {
        return $this->genres;
    }

    public function getDrinks(): array
    {
        return $this->drinks;
    }

    public static function getStatusName(): array
    {
        return [
            self::STATUS_DANCE => 'let\'s dance',
            self::STATUS_DRUNK => 'go drunk',
            self::STATUS_AWAY => 'i am tired'
        ];
    }

    /**
     * debug method
     *
     * @return string
     */
    abstract function sayHello(): string;
}