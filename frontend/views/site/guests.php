<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Guests';

\yii\widgets\Pjax::begin();

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'national',
        [
            'attribute' => 'status',
            'content' => function($data) {
                return \common\abstracts\AGuest::getStatusName()[$data->status];
            }
        ],
        'genres',
        'kinds'
    ]
]);

echo \yii\helpers\Html::a("Обновить", ['site/guests'], ['class' => 'btn btn-lg btn-primary']);

\yii\widgets\Pjax::end();