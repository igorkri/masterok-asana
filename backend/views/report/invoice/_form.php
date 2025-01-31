<?php

use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\report\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container bg-light p-4 shadow rounded">
    <div class="invoice-form">
        <?php $form = ActiveForm::begin(); ?>

        <div class="card p-4 mb-3 shadow-sm">
            <h4 class="card-title text-primary">Основна інформація</h4>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'payer_id')->dropDownList(
                        \yii\helpers\ArrayHelper::map(\common\models\report\Payer::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                        ['prompt' => 'Виберіть платника', 'class' => 'form-control']
                    ) ?>
                </div>
            </div>
            <div class="row row-cols-md-2 g-3">
                <div class="col">
                    <?= $form->field($model, 'qty')->textInput(['class' => 'form-control']) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                </div>
            </div>
        </div>

        <div class="row row-cols-md-2 g-3">
            <div class="col">
                <div class="card p-4 shadow-sm">
                    <h5 class="card-title text-success">Данні рахунку</h5>
                    <?= $form->field($model, 'title_invoice')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                    <?= $form->field($model, 'invoice_no')->textInput(['class' => 'form-control']) ?>
                    <?= $form->field($model, 'date_invoice')->widget(
                        DatePicker::class,
                        [
                            'options' => ['placeholder' => 'Виберіть дату', 'class' => 'form-control'],
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                            ]
                        ]
                    ) ?>
                </div>
            </div>
            <div class="col">
                <div class="card p-4 shadow-sm">
                    <h5 class="card-title text-danger">Данні акту</h5>
                    <?= $form->field($model, 'title_act')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                    <?= $form->field($model, 'act_no')->textInput(['class' => 'form-control']) ?>
                    <?= $form->field($model, 'date_act')->widget(
                        DatePicker::class,
                        [
                            'options' => ['placeholder' => 'Виберіть дату', 'class' => 'form-control'],
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                            ]
                        ]
                    ) ?>
                </div>
            </div>
        </div>

        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="text-center mt-4">
                <?= Html::submitButton(
                    $model->isNewRecord ? 'Создать' : 'Обновить',
                    ['class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']
                ) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
