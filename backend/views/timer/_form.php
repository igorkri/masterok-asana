<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Timer */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container">
    <div class="timer-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php echo $form->field($model, 'task_gid')->hiddenInput(['maxlength' => true])->label(false) ?>

        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'time')->widget(
                    \kartik\time\TimePicker::classname(),
                    [
                        'pluginOptions' => [
                            'showSeconds' => true, // show seconds
                            'showMeridian' => false, // 24 hours format
                            'minuteStep' => 1, // minute step
                            'secondStep' => 5, // second step
                            'defaultTime' => '00:00:00', // default time
                        ]
                    ]
                ) ?></div>
            <div class="col-md-3"><?= $form->field($model, 'minute')->textInput() ?></div>
            <div class="col-md-3">

                <?php echo $form->field($model, 'coefficient')->dropDownList(
                    \common\models\Timer::$coefficientList
                ) ?>
            </div>
            <div class="col-md-3"><?= $form->field($model, 'status')->dropDownList(
                    \common\models\Timer::$statusList,
                    ['prompt' => '']
                ) ?></div>
        </div>

        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'created_at')->textInput(['readonly' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'updated_at')->textInput(['readonly' => true]) ?>
            </div>
        </div>


        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
