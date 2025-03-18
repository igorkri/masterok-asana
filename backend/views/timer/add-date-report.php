<?php

use kartik\daterange\DateRangePicker;



?>

<?php $form = \kartik\form\ActiveForm::begin([
    'action' => 'update-date-report',
    'method' => 'post'
]); ?>

<?php echo $form->field($model, 'date_report', [
    'addon'=>['prepend'=>['content'=>'<i class="fas fa-calendar-alt"></i>']],
    'options'=>['class'=>'drp-container mb-2']
])->widget(DateRangePicker::classname(), [
    'useWithAddon'=>true
]);?>

<?php echo $form->field($model, 'status')->dropDownList(\common\models\Timer::$statusList); ?>

<?php echo $form->field($model, 'pks')->textInput() ?>

<?php \kartik\form\ActiveForm::end(); ?>
