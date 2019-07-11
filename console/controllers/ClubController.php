<?php
namespace console\controllers;

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

        /**
         * @var $club \common\models\Club
         */
        $club = $container->get('common\models\Club');
        $container->invoke([$club, 'playMusic']);
        $this->stdout('Play ' . $club->getMusic() . PHP_EOL);
        // TODO: save club

        // Create few random guests for our location
        $nationals = [\common\models\guests\RussianGuest::class, \common\models\guests\GermanGuest::class];

        for ($i = 0; $i < self::START_GUESTS; $i++) {
            $guestNational = $nationals[array_rand($nationals, 1)];

            /**
             * @var $guest \common\interfaces\IGuestCreate | \common\abstracts\AGuest | \common\interfaces\IGuestBehavior
             */
            $guest = new $guestNational();

            $container->invoke([$guest, 'musicSetting']);
            $container->invoke([$guest, 'drinkSetting']);

            $container->invoke([$guest, 'setMood'], ['genre' => $club->getMusic(), 'kinds' => $club->getKindDrinks()]);

            // TODO: save guest

            $this->stdout($guest->sayHello() . PHP_EOL);
        }
    }

    public function actionOptimize()
    {
        // TODO: change massive genre
    }

    public function actionProcess()
    {
        // TODO: generate new guests and change mood after each new music
    }
}