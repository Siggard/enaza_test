<?php
namespace common\models\data;

use common\models\interfaces\IPlaylist;
use common\models\{GermanGuest, RussianGuest};
use yii\redis\ActiveRecord;

class Playlist extends ActiveRecord implements IPlaylist
{
    public function create($isKeys = false): array
    {
        // if need, can use to other DAO (mysql, sqlite e.t.c)
        // $data = self::getAll();

        $data = [
            'ROCK' => [RussianGuest::class],
            'POP' => [],
            'RAP' => [],
            'HOUSE' => [GermanGuest::class]
        ];

        // TODO: add cache
        return $isKeys ? array_keys($data) : $data;
    }

    public static function getAll()
    {
        return static::find()
            ->asArray()
            ->all();
    }
}