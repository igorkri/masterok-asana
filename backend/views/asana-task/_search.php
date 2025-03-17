<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\search\TaskSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'gid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'assignee_gid') ?>

    <?= $form->field($model, 'assignee_name') ?>

    <?php // echo $form->field($model, 'assignee_status') ?>

    <?php // echo $form->field($model, 'section_project_gid') ?>

    <?php // echo $form->field($model, 'section_project_name') ?>

    <?php // echo $form->field($model, 'completed') ?>

    <?php // echo $form->field($model, 'completed_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'due_on') ?>

    <?php // echo $form->field($model, 'start_on') ?>

    <?php // echo $form->field($model, 'notes') ?>

    <?php // echo $form->field($model, 'permalink_url') ?>

    <?php // echo $form->field($model, 'project_gid') ?>

    <?php // echo $form->field($model, 'workspace_gid') ?>

    <?php // echo $form->field($model, 'modified_at') ?>

    <?php // echo $form->field($model, 'resource_subtype') ?>

    <?php // echo $form->field($model, 'num_hearts') ?>

    <?php // echo $form->field($model, 'num_likes') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
