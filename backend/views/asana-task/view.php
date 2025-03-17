<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Task $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

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
            'gid',
            'name:ntext',
            'assignee_gid',
            'assignee_name',
            'assignee_status',
            'section_project_gid',
            'section_project_name',
            'completed',
            'completed_at',
            'created_at',
            'due_on',
            'start_on',
            'notes:ntext',
            'permalink_url:url',
            'project_gid',
            'workspace_gid',
            'modified_at',
            'resource_subtype',
            'num_hearts',
            'num_likes',
        ],
    ]) ?>

</div>
