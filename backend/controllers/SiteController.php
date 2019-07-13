<?php
namespace backend\controllers;

use yii\rest\Controller;

class SiteController extends Controller
{
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
}