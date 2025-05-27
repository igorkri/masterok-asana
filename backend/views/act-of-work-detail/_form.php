<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ActOfWorkDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="act-of-work-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'act_of_work_id')->textInput() ?>

    <?= $form->field($model, 'time_id')->textInput() ?>

    <?= $form->field($model, 'task_id')->textInput() ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'project')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'task')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hours')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
