<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container">
    <div class="task-view">
    
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
</div>
