<?php

use igorkri\ckeditor\CKEditor;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use igorkri\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $model common\models\report\Payer */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container">
    <div class="payer-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'contract')->textInput(['maxlength' => true]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'director')->textInput(['maxlength' => true]) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'director_case')->textInput(['maxlength' => true]) ?></div>
        </div>

        <?= $form->field($model, 'requisites')->widget(CKEditor::class, [
            'id' => 'notes',
            'editorOptions' =>
                ElFinder::ckeditorOptions('elfinder', [
                    'preset' => 'basic',
                    'height' => 200,
                    'language' => 'uk',
                    'controller' => 'elfinder',
                ]),
        ]) ?>

        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
        
    </div>
</div>
