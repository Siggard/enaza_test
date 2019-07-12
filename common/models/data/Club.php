<?php
namespace common\models\data;

use yii\redis\ActiveRecord;

/**
 * Class Club
 *
 * @property integer id
 * @property integer time
 * @property string kinds
 * @property string play
 * @property string genres
 *
 * @package common\models\data
 */
class Club extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'play', 'genres', 'time', 'kinds'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kinds', 'play', 'genres', 'time'], 'required'],
            [['kinds', 'play', 'genres'], 'string'],
            ['time', 'integer']
        ];
    }

    public static function getSingle()
    {
        return static::find()->one();
    }
}