<?php

use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AccountingEntries */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container">
    <div class="accounting-entries-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'counterparty')->dropDownList($model->getCounterparty(), ['prompt' => 'Виберіть кореспондента']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'debit')->textInput(['maxlength' => true]) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'credit')->textInput(['maxlength' => true]) ?></div>
        </div>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'document_at')
                    ->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Виберіть дату'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'language' => 'uk',

                        ]
                    ]);
                ?></div>
            <div class="col-md-6"><?= $form->field($model, 'created_at')->textInput() ?></div>
        </div>

        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
