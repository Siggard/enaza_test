<?php
namespace common\models\data;

use common\models\interfaces\IAssortment;
use common\models\RussianGuest;
use yii\redis\ActiveRecord;

class Assortment extends ActiveRecord implements IAssortment
{
    public function create($isKeys = false): array
    {
        // if need, can use to other DAO (mysql, sqlite e.t.c)
        // $data = self::getAll();

        $data = [
            'B52' => [],
            'VODKA' => [RussianGuest::class],
            'WHISKEY' => []
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