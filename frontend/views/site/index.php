<?php

/* @var $this yii\web\View */
/* @var $model \common\models\data\Club */

$this->title = 'Club info';
$time = strtotime('now') - $model->playTime;
?>
<table class="table table-hover table-bordered">
    <tbody>
        <tr class="success">
            <th>Play now</th>
            <td><b><?= $model->playGenre ?></b></td>
        </tr>
        <tr class="success">
            <th>Play time current music</th>
            <td><?= $time ?>s</td>
        </tr>
        <tr>
            <th>Playlist in current club</th>
            <td><?= $model->genres ?></td>
        </tr>
        <tr>
            <th>Kind of drinks in current club</th>
            <td><?= $model->kinds ?></td>
        </tr>
    </tbody>
</table>