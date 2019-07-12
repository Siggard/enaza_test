<?php
namespace backend\controllers;

use common\models\data\{
    Guest, Club, User
};
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use common\factorys\{
    ClubFactory, GuestFactory
};

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

    public function actionOptimize()
    {
        $container = new \yii\di\Container;

        /**
         * @var $music \common\models\Music
         */
        $container->set('common\interfaces\IPlaylist', 'common\models\data\Playlist');
        $music = $container->get('common\models\Music');

        /**
         * @var $drink \common\models\Drink
         */
        $container->set('common\interfaces\IAssortment', 'common\models\data\Assortment');
        $drink = $container->get('common\models\Drink');

        $genres = array_fill_keys(array_keys($music->getAllGenres()), 0);

        $guests = Guest::getAll();
        foreach ($guests as $rGuest) {
            $guest = GuestFactory::makeGuest($rGuest, $music, $drink);
            foreach(array_keys($guest->getGenres()) as $genre) {
                $genres[$genre]++;
            }
        }

        arsort($genres);
        $top = array_shift(array_keys($genres));

        $rClub = Club::getSingle();
        $club = ClubFactory::makeClub($rClub, $music, $drink);

        if ($club->getPlayGenre() != $top) {
            $club->setPlayGenre($top);
            $club->setPlayTime(strtotime('now'));

            $rClub->attributes = $club->toSave();
            if (!$rClub->save()) {
                throw new \RedisException('Error save club');
            }
        }

        return $club->getPlayGenre();
    }

    public function auth($login, $password)
    {
        $user = User::find()->where(['login' => $login])->one();

        if ($user->validatePassword($password)) {
            return $user;
        }

        return null;
    }
}
