<?php
namespace common\models\data;

use yii\redis\ActiveRecord;

/**
 * Class Club
 *
 * @property integer id
 * @property integer playTime
 * @property string kinds
 * @property string playGenre
 * @property string genres
 *
 * @package common\models\data
 */
class Club extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'playGenre', 'genres', 'playTime', 'kinds'];
    }

    public function rules()
    {
        return [
            [['kinds', 'playGenre', 'genres', 'playTime'], 'required'],
            [['kinds', 'playGenre', 'genres'], 'string'],
            ['playTime', 'integer']
        ];
    }

    public static function getSingle()
    {
        return static::find()->one();
    }
}