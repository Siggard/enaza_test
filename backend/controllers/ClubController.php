<?php
namespace backend\controllers;

use common\models\data\{
    Club, Guest, User
};
use common\models\NightClub;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;

class ClubController extends ActiveController
{
    public $modelClass = 'common\models\data\Club';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'auth' => [$this, 'auth'],
            'only' => ['optimize']
        ];
        return $behaviors;
    }

    /**
     * @return string
     * @throws \RedisException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionOptimize()
    {
        $club = Club::getSingle();
        $clubModel = (new NightClub())->loadSingle($club);

        $top = $clubModel->topGenre(Guest::getAll());

        if ($clubModel->getPlayGenre() != $top) {
            $clubModel->setPlayMusic($top);

            $club->attributes = $clubModel->toSave();
            if (!$club->validate() || !$club->save()) {
                throw new \RedisException('Error save club');
            }
        }

        return $clubModel->getPlayGenre();
    }

    /**
     * @param $login
     * @param $password
     * @return array|null|\yii\redis\ActiveRecord
     */
    public function auth($login, $password)
    {
        /**
         * @var $user \common\models\data\User
         */
        $user = User::findByLogin($login);

        if ($user->validatePassword($password)) {
            return $user;
        }

        return null;
    }
}
