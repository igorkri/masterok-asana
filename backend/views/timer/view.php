<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Timer */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Timers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container">
    <div class="timer-view">
    
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
            'task_gid',
            'time',
            'minute',
            'coefficient',
            'comment:ntext',
            'status',
            'created_at',
            'updated_at',
            ],
        ]) ?>

    </div>
</div>
