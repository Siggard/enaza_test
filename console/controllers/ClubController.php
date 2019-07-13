<?php
namespace console\controllers;

use Codeception\Exception\ConfigurationException;
use common\models\factories\{ClubFactory, GuestFactory};
use common\models\data\{
    Guest, Club, Playlist, User
};
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

            foreach (Guest::getAll() as $rGuest) {
                /**
                 * @var $guest \common\models\interfaces\IGuestBehavior | \common\models\base\AGuest
                 */
                $guest = GuestFactory::makeGuest($rGuest, $music, $drink);
                $container->invoke([$guest, 'moodSetting'], ['kinds' => $club->getKinds(), 'genre' => $club->getPlayGenre()]);

                Guest::quickSave($guest->getId(), $guest->toSave());
            }

            // TODO: del tired "X" guests and generate new "Y" guests

            $result = $club->checkNextMusic($rClub);
            $this->stdout($result['genre'] . ' time to next ' . $result['time'] . PHP_EOL);

            sleep(1);
        }
    }

    /**
     * @param $genre
     * @throws ConfigurationException
     */
    public function actionPlay($genre)
    {
        $genre = mb_strtoupper($genre, 'UTF-8');

        $genres = (new Playlist())->create(true);
        if (!in_array($genre, $genres)) {
            throw new ConfigurationException('This genre is not supported');
        }

        if (!(Club::getSingle())
            ->setPlayGenre($genre)
            ->setPlayTime()
            ->save()) {
            throw new \Exception('Save error');
        }

        $this->stdout('Play successfully!');
    }
}