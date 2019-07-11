<?php
namespace common\models\data;

use common\abstracts\AGuest;
use yii\db\ActiveRecord;

class Guest extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%guest}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => AGuest::STATUS_AWAY],
            ['status', 'in', 'range' => [AGuest::STATUS_AWAY, AGuest::STATUS_DRUNK, AGuest::STATUS_DANCE]],
        ];
    }
}