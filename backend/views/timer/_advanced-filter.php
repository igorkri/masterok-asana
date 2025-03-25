<?php

use common\models\Timer;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use common\models\Project;

/* @var $searchModel backend\models\search\TimerSearch */


$form = ActiveForm::begin([
    'method' => 'get',
    'action' => ['index'],
    'options' => ['data-pjax' => true],
]);

?>

<?= $form->field($searchModel, 'exclude')->radioList(
    [
        'yes' => 'Не враховувати',
        'no' => 'Враховувати тільки',
    ],
    ['value' => $selected['exclude']]
)->label('Дія з проєктам') ?>

<?= $form->field($searchModel, 'project_id')->widget(Select2::class, [
    'data' => Project::find()->select('name')->indexBy('id')->column(),
    'value' => $selected['projectIds'],
    'options' => [
        'placeholder' => 'Виберіть проєкти ...',
        'multiple' => true,
        'value' => $selected['projectIds'] ?? [],
    ],
    'pluginOptions' => [
        'allowClear' => true,
    ],
])->label('Проєкт') ?>


<div class="form-group">
    <?= Html::submitButton('Застосувати', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerCss(<<<CSS
.select2-container {
    width: 100% !important;
}
.select2-selection {
    min-height: 38px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
}
CSS);
?>
