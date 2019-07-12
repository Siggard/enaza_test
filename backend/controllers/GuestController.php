<?php
namespace backend\controllers;

use yii\rest\ActiveController;

class GuestController extends ActiveController
{
    public $modelClass = 'common\models\data\Guest';
}