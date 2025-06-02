<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Json;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var common\models\ActOfWork $model */
/** @var yii\widgets\ActiveForm $form */


/**
 * [
 * 0 => 'second_half_month'
 * 1 => 'May'
 * 2 => '2025'
 * ]
 */

$periodsArr = Json::decode($model->period);
$periodData = [
    'type' => $periodsArr[0]['type'] ?? '',
    'month' => $periodsArr[0]['month'] ?? '',
    'year' => $periodsArr[0]['year'] ?? '',
];
//debugDie($periodData);
$selectedPeriodType = $periodData['type'] ?? '';
$selectedYear = $periodData['year'] ?? date('Y');
$selectedMonth = $periodData['month'] ?? date('n');
?>
    <div id="top" class="sa-app__body">
        <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
            <div class="container">
                <div class="py-5">
                    <div class="row g-4 align-items-center">
                        <div class="col">
                            <h1 class="h3 m-0">Акт: <?= Html::encode($model->number ?: 'новий') ?></h1>
                        </div>
                        <div class="col-auto d-flex">
                        </div>
                    </div>
                </div>
                <div class="act-of-work-form">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-black">
                            <h4 class="mb-0"><?= $model->isNewRecord ? 'Створення' : 'Редагування' ?> акту виконаних робіт</h4>
                        </div>
                        <div class="card-body">
                            <?php $form = ActiveForm::begin([
                                'id' => 'act-form',
                                'enableClientValidation' => true,
                                'validateOnSubmit' => true,
                            ]); ?>

                            <div class="form-group mt-4 text-end">
                            <?= Html::a('← Назад', ['/act-of-work/index'], ['class' => 'btn btn-link']) ?>
                                <?= Html::submitButton($model->isNewRecord ? 'Створити' : 'Зберегти', ['class' => 'btn btn-success']) ?>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'status')->dropDownList(\common\models\ActOfWork::$statusList, [
                                        'prompt' => 'Виберіть статус'
                                    ]) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'date')->widget(DatePicker::class, [
                                        'options' => ['placeholder' => 'Оберіть дату...'],
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd',
                                            'todayHighlight' => true,
                                        ]
                                    ]) ?>
                                </div>

                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'total_amount')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'paid_amount')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header bg-secondary text-black">
                                    <h5 class="mb-0">Період виконання робіт</h5>
                                </div>
                                <div class="card-body row g-3">
                                    <div class="col-md-3">
                                        <?=$form->field($model, 'period_type')->dropDownList(\common\models\ActOfWork::$periodTypeList, ['prompt' => 'Виберіть тип'])?>
                                    </div>
                                    <div id="year-container" class="col-md-3">
                                        <?=$form->field($model, 'period_year')->dropDownList(\common\models\ActOfWork::$yearsList, ['prompt' => 'Виберіть рік'])?>
                                    </div>
                                    <div id="month-container" class="col-md-3">
                                        <?= $form->field($model, 'period_month')->dropDownList(
                                            \common\models\ActOfWork::$monthsList,
                                            ['class' => 'form-select', 'id' => 'period-month', 'prompt' => 'Виберіть місяць']
                                        ) ?>
                                    </div>
                                    <div id="month-container" class="col-md-3">
                                        <?= $form->field($model, 'type')->dropDownList(
                                            \common\models\ActOfWork::$type,
                                            ['prompt' => 'Виберіть тип акту', 'id' => 'period-type']
                                        ) ?>

                                        <?= $form->field($model, 'telegram_status')->dropDownList(
                                            \common\models\ActOfWork::$telegramStatusList,
                                            ['prompt' => 'Виберіть', 'id' => 'period-type']
                                        ) ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>


                            <?php if (!$model->isNewRecord): ?>
                                <?=Html::a(
                                    '<i class="fas fa-file-excel"></i> ' . basename($model->file_excel),
                                    $model->file_excel,
                                    ['target' => '_blank', 'title' => 'Завантажити Excel файл', 'data-pjax' => 0])?>
                                <?= $form->field($model, 'file_excel')->textInput(['maxlength' => true]) ?>
                            <?php endif; ?>

                            <?php if (!$model->isNewRecord ): ?>
                            <div class="card mt-4">
                                <div class="card-header bg-secondary text-black">
                                    <h5 class="mb-0">Деталі</h5>
                                </div>
                                <div class="card-body">
                                    <?= $this->render('detail/index', [
                                        'model' => $model,
                                        'searchModel' => $searchModel ?? null,
                                        'dataProvider' => $dataProvider ?? null,
                                    ]) ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$js = <<<JS
$(document).ready(function() {
    togglePeriodFields($('#period-type').val());

    $('#act-form').on('beforeSubmit', function() {
        var periodData = {
            type: $('#period-type').val(),
            year: $('#period-year').val(),
            month: $('#period-month').val(),
            week: $('#period-week').val(),
            day: $('#period-day').val()
        };
        $('#period-json').val(JSON.stringify([periodData])); // меняем на массив
        // $('#period-json').val(JSON.stringify([periodData.type, periodData.month, periodData.year]));
        
        return true;
    });
});

function togglePeriodFields(type) {
    $('#week-container, #day-container').hide();
    $('#month-container, #year-container').show();

    switch(type) {
        case 'year':
            $('#month-container').hide();
            break;
        case 'month':
        case 'first_half_month':
        case 'second_half_month':
            break;
        case 'week':
            $('#week-container').show();
            break;
        case 'day':
            $('#day-container').show();
            break;
    }
}
JS;
$this->registerJs($js);

