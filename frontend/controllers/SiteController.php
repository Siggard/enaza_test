<?php
namespace frontend\controllers;

use common\models\data\Club;
use common\models\data\Guest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'model' => Club::getSingle()
        ]);
    }

    public function actionGuests()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Guest::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('guests', [
            'dataProvider' => $dataProvider
        ]);
    }
}
