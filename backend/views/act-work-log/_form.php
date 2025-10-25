<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ActWorkLog */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container">
    <div class="act-work-log-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'act_of_work_id')->textInput() ?>

    <?= $form->field($model, 'act_of_work_detail_id')->textInput() ?>

    <?= $form->field($model, 'timer_id')->textInput() ?>

    <?= $form->field($model, 'task_id')->textInput() ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

  
        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
        
    </div>
</div>
