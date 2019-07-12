<?php
namespace common\models\data;

use common\interfaces\IPlaylist;
use common\models\guests\{GermanGuest, RussianGuest};
use yii\redis\ActiveRecord;

class Playlist extends ActiveRecord implements IPlaylist
{
    public function create(): array
    {
        return [
            'ROCK' => [RussianGuest::class],
            'POP' => [],
            'RAP' => [],
            'HOUSE' => [GermanGuest::class]
        ];

        // if need, can use to other DAO (mysql, sqlite e.t.c)
        // return self::getAll();
    }

    public static function getAll()
    {
        return static::find()
            ->asArray()
            ->all();
    }
}