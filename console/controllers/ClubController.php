<?php
namespace console\controllers;

use common\factorys\ClubFactory;
use common\factorys\GuestFactory;
use common\models\data\{
    Guest, Club, User
};
use common\models\NightClub;
use yii\console\Controller;

class ClubController extends Controller
{
    const START_GUESTS = 20;

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

        $this->actionProcess();
    }

    public function actionProcess()
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

        while(true) {

            $rClub = Club::getSingle();
            $club = ClubFactory::makeClub($rClub, $music, $drink);

            $guests = Guest::getAll();

            foreach ($guests as $rGuest) {
                /**
                 * @var $guest \common\interfaces\IGuestBehavior | \common\abstracts\AGuest
                 */
                $guest = GuestFactory::makeGuest($rGuest, $music, $drink);
                $container->invoke([$guest, 'moodSetting'], ['kinds' => $club->getKinds(), 'genre' => $club->getPlayGenre()]);

                $rGuest = Guest::findOne($guest->getId());
                $rGuest->attributes = $guest->toSave();
                $rGuest->save();
                if (!$rGuest->save()) {
                    throw new \RedisException('Error save guest');
                }

                // TODO: del tired guests and generate new
            }

            $time = $club->getLeftMusicTime();
            if ($time == 0) {
                $club->playRandomMusic();
                $this->stdout('Next song! ' . $club->getPlayGenre() . PHP_EOL);

                $rClub->attributes = $club->toSave();
                if (!$rClub->save()) {
                    throw new \RedisException('Error save club');
                }
            } else {
                $this->stdout('Time to next ' . $time . PHP_EOL);
            }

            sleep(1);
        }
    }
}