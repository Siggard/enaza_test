<?php
namespace common\models\data;

use yii\base\NotSupportedException;
use yii\redis\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $login
 * @property string $password_hash
 * @property string $auth_key
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public function attributes()
    {
        return ['id', 'login', 'auth_key', 'password_hash', 'password'];
    }

    public static function findIdentity($id)
    {
        return static::find()->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPasswordHash($password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }
}