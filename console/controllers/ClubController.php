<?php
namespace console\controllers;

use common\models\factories\{ClubFactory, GuestFactory};
use common\models\data\{Guest, Club, User};
use common\models\NightClub;
use yii\console\Controller;

class ClubController extends Controller
{
    const START_GUESTS = 20;

    /**
     * @throws \RedisException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionInit()
    {
        // destroy previous session
        \Yii::$app->redis->executeCommand('flushall');

        // crete new club
        $club = (new NightClub())->create(new Club());
        $this->stdout('Play ' . $club->getPlayGenre() . PHP_EOL);

        // fill club
        for ($i = 0; $i < self::START_GUESTS; $i++) {
            $guestNational = \Yii::$app->params['nationals'][array_rand(\Yii::$app->params['nationals'], 1)];

            /**
             * @var $guest \common\models\interfaces\IGuestCreate | \common\models\base\AGuest | \common\models\interfaces\IGuestBehavior
             */
            $guest = (new $guestNational())->create($club, new Guest());
            $this->stdout($guest->sayHello() . PHP_EOL);
        }

        // gen test admin
        (new User())->testGenerate();

        // start live process
        $this->actionProcess();
    }

    /**
     * @throws \RedisException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionProcess()
    {
        $container = new \yii\di\Container;

        /**
         * @var $music \common\models\Music
         */
        $container->set('common\models\interfaces\IPlaylist', 'common\models\data\Playlist');
        $music = $container->get('common\models\Music');

        /**
         * @var $drink \common\models\Drink
         */
        $container->set('common\models\interfaces\IAssortment', 'common\models\data\Assortment');
        $drink = $container->get('common\models\Drink');

        while(true) {

            $rClub = Club::getSingle();
            $club = ClubFactory::makeClub($rClub, $music, $drink);

            $guests = Guest::getAll();

            foreach ($guests as $rGuest) {
                /**
                 * @var $guest \common\models\interfaces\IGuestBehavior | \common\models\base\AGuest
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