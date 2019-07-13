<?php
namespace frontend\controllers;

use common\models\data\{Club, Guest};
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * Class SiteController, display club and guests status
 *
 * @package frontend\controllers
 */
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

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
