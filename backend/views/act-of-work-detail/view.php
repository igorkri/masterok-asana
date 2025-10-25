<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\ActOfWorkDetail $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Act Of Work Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="act-of-work-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'act_of_work_id',
            'time_id:datetime',
            'task_id',
            'project_id',
            'project',
            'task',
            'description:ntext',
            'amount',
            'hours',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
