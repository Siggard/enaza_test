<?php
namespace console\controllers;

use common\models\data\{
    Guest, Club, User
};
use common\models\NightClub;
use yii\console\Controller;

class ClubController extends Controller
{
    const START_GUESTS = 10;

    public function actionInit()
    {
        $container = new \yii\di\Container;
        $container->set('common\interfaces\IPlaylist', 'common\models\data\Playlist');
        $container->get('common\models\Music');
        $container->set('common\interfaces\IAssortment', 'common\models\data\Assortment');
        $container->get('common\models\Drink');

        $club = new NightClub();
        $container->invoke([$club, 'musicSetting']);
        $container->invoke([$club, 'drinkSetting']);
        $club->hideKind();
        $club->playRandomMusic();

        $this->stdout('Play ' . $club->getPlayGenre() . PHP_EOL);

        \Yii::$app->redis->executeCommand('flushall');

        $rClub = new Club();
        $rClub->attributes = $club->toSave();
        if (!$rClub->save()) {
            throw new \RedisException('Error save club');
        }

        // Create few random guests for our location
        for ($i = 0; $i < self::START_GUESTS; $i++) {
            $guestNational = \Yii::$app->params['nationals'][array_rand(\Yii::$app->params['nationals'], 1)];

            /**
             * @var $guest \common\interfaces\IGuestCreate | \common\abstracts\AGuest | \common\interfaces\IGuestBehavior
             */
            $guest = new $guestNational();

            $container->invoke([$guest, 'musicSetting']);
            $container->invoke([$guest, 'drinkSetting']);
            $container->invoke([$guest, 'moodSetting'], ['kinds' => $club->getKinds(), 'genre' => $club->getPlayGenre()]);

            $rGuest = new Guest();
            $rGuest->attributes = $guest->toSave();
            $rGuest->save();
            if (!$rGuest->save()) {
                throw new \RedisException('Error save guest');
            }

            $this->stdout($guest->sayHello() . PHP_EOL);
        }

        // create test user for access API
        $user = new User();
        $user->login = \Yii::$app->params['testLogin'];
        $user->password = \Yii::$app->params['testPassword'];
        $user->setPasswordHash(\Yii::$app->params['testPassword']);
        $user->save();
    }

    public function actionProcess()
    {
        // TODO: generate new guests and change mood after each new music

    }
}