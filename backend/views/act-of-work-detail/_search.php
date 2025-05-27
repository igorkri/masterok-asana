<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\search\ActOfWorkDetailSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="act-of-work-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'act_of_work_id') ?>

    <?= $form->field($model, 'time_id') ?>

    <?= $form->field($model, 'task_id') ?>

    <?= $form->field($model, 'project_id') ?>

    <?php // echo $form->field($model, 'project') ?>

    <?php // echo $form->field($model, 'task') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'hours') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
