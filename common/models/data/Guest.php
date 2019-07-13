<?php
namespace common\models\data;

use common\models\base\AGuest;
use yii\redis\ActiveRecord;

/**
 * Class Guest
 *
 * @property integer id
 * @property integer mood
 * @property string national
 * @property string kinds
 * @property string genres
 *
 * @package common\models\data
 */
class Guest extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'mood', 'kinds', 'genres', 'national'];
    }

    public function rules()
    {
        return [
            ['mood', 'default', 'value' => AGuest::STATUS_AWAY],
            ['mood', 'in', 'range' => [AGuest::STATUS_AWAY, AGuest::STATUS_DRUNK, AGuest::STATUS_DANCE]],
            [['kinds', 'genres', 'national'], 'required'],
            ['national', 'in', 'range' => array_keys(\Yii::$app->params['nationals'])],
            [['kinds', 'genres'], 'string']
        ];
    }

    public static function getAll(): array
    {
        return static::find()
            ->indexBy('id')
            ->all();
    }
}