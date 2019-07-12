<?php
namespace console\controllers;

use common\models\data\{Guest, Club};
use common\factorys\{
    ClubFactory, GuestFactory
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

        $guests = Guest::find()->all();
        foreach ($guests as $rGuest) {
            $guest = GuestFactory::makeGuest($rGuest, $music, $drink);

            foreach($guest->getGenres() as $genre) {
                $genres[$genre]++;
            }

            $this->stdout($guest->sayHello() . PHP_EOL);
        }

        arsort($genres);
        $top = array_shift(array_keys($genres));

        $this->stdout('Top genre now it is — ' . $top . '!' . PHP_EOL);

        $rClub = Club::getSingle();
        $club = ClubFactory::makeClub($rClub, $music, $drink);

        if ($club->getPlayGenre() != $top) {
            $this->stdout('Change old ' . $club->getPlayGenre());

            $club->setPlayGenre($top);
            $club->setPlayTime(strtotime('now'));

            $this->stdout(' to best music ever!' . PHP_EOL);

            $rClub->attributes = $club->toSave();
            if (!$rClub->save()) {
                throw new \RedisException('Error save club');
            }
        }
    }

    public function actionProcess()
    {
        // TODO: generate new guests and change mood after each new music
    }
}