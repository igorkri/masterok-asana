<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;


/** @var yii\web\View $this */
/** @var frontend\models\GenerateImage $model */

$this->title = 'Генерування випадкових зображень';
?>

<div class="image-generate-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr>
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'width')->textInput(['type' => 'number', 'min' => 1]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'height')->textInput(['type' => 'number', 'min' => 1]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'count')->textInput(['type' => 'number', 'min' => 1]) ?></div>
        <div class="col-md-6"><?php echo $form->field($model, 'format')->dropDownList($model->formats) ?></div>
    </div>




    <?php // $form->field($model, 'query')->dropDownList($model->queries) ?>

    <div class="form-group">
        <?= Html::submitButton('Згенерувати', ['class' => 'btn btn-primary', ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
