<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ActWorkLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Act Work Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container">
    <div class="act-work-log-view">
    
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
            'act_of_work_id',
            'act_of_work_detail_id',
            'timer_id:datetime',
            'task_id',
            'project_id',
            ],
        ]) ?>

    </div>
</div>
