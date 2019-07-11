<?php
namespace common\models\data;

use common\interfaces\IPlaylist;
use common\models\guests\GermanGuest;
use common\models\guests\RussianGuest;
use yii\db\ActiveRecord;

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

        // return $this->getAll();
    }

    public function getAll()
    {
        return static::find()
            ->asArray()
            ->all();
    }
}