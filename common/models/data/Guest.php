<?php
namespace common\models\data;

use common\abstracts\AGuest;
use yii\redis\ActiveRecord;

/**
 * Class Guest
 *
 * @property integer id
 * @property integer status
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
        return ['id', 'status', 'kinds', 'genres', 'national'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => AGuest::STATUS_AWAY],
            ['status', 'in', 'range' => [AGuest::STATUS_AWAY, AGuest::STATUS_DRUNK, AGuest::STATUS_DANCE]],
            [['kinds', 'genres', 'national'], 'required'],
            ['national', 'in', 'range' => array_keys(\Yii::$app->params['nationals'])],
            [['kinds', 'genres'], 'string']
        ];
    }
}