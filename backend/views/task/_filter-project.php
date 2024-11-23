<?php

use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TaskSearch */
?>

<div class="sa-layout__sidebar">
    <div class="sa-layout__sidebar-header">
        <div class="sa-layout__sidebar-title">
            Фільтри
        </div>
        <button type="button" class="sa-close sa-layout__sidebar-close" aria-label="Close"
                data-sa-layout-sidebar-close=""></button>
    </div>
    <div class="sa-layout__sidebar-body sa-filters">
        <?php $form = ActiveForm::begin([
            'id' => 'task-filter-form',
            'action' => Url::to(Yii::$app->request->url),
            'method' => 'get',
            'options' => ['class' => 'form-horizontal', 'data-pjax' => true],
        ]); ?>

        <ul class="sa-filters__list">
            <li class="sa-filters__item">
                <div class="sa-filters__item-title">Виконавець</div>
                <div class="sa-filters__item-body">
                    <ul class="list-unstyled m-0 mt-n2">
                        <?php foreach (\common\models\Task::find()->select('assignee_name')->where(['project_gid' => $project->gid])->distinct()->orderBy('assignee_name ASC')->all() as $assignee): ?>
                            <?php if (!$assignee->assignee_name || $assignee->assignee_name == 'Private User') continue; ?>
                            <li>
                                <label class="d-flex align-items-center pt-2">
                                    <?= $form->field($searchModel, 'assignee_name[]')
                                        ->checkbox([
                                            'value' => Html::encode($assignee->assignee_name),
                                            'label' => Html::encode($assignee->assignee_name),
                                            'class' => 'form-check-input m-0 me-3 fs-exact-16 assignee-filter',
                                            'checked' => in_array($assignee->assignee_name, (array)$searchModel->assignee_name)
                                        ])->label(false) ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>

            <li class="sa-filters__item">
                <div class="sa-filters__item-title">Статус</div>
                <div class="sa-filters__item-body">
                    <ul class="list-unstyled m-0 mt-n2">
                        <?php foreach (\common\models\Task::find()->select('section_project_name')->where(['project_gid' => $project->gid])->distinct()->all() as $section): ?>
                            <li>
                                <label class="d-flex align-items-center pt-2">
                                    <?= $form->field($searchModel, 'section_project_name[]')
                                        ->checkbox([
                                            'value' => Html::encode($section->section_project_name),
                                            'label' => Html::encode($section->section_project_name),
                                            'class' => 'form-check-input m-0 me-3 fs-exact-16',
                                            'checked' => in_array($section->section_project_name, (array)$searchModel->section_project_name)
                                        ])->label(false) ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>

            <li class="sa-filters__item">
                <div class="sa-filters__item-title">Дата створення</div>
                <?php
                echo DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'id' => 'filter_created_at',
                    'useWithAddon' => true,
//                    'containerOptions' => [
//                        'class' => 'date-filter-container', // Дополнительный класс для стилизации
//                        'style' => 'width: 100%',
//                    ],
                    'language' => 'uk-UA',
                    'hideInput' => false,
                    'presetDropdown' => true,
                    'convertFormat' => true,
                    'value' => $searchModel->created_at, // Добавлено для отображения текущего значения фильтра
                    'pluginOptions' => [
                        'format' => 'dd.MM.yyyy',
                        'separator' => '-',
                        'opens' => 'left',
                        'autoUpdateInput' => true, // Автообновление поля ввода при выборе диапазона
                    ],
//                    'pluginEvents' => [
//                        'apply.daterangepicker' => "function(ev, picker) {
//                $('#task-filter-form').submit(); // Отправка формы при выборе даты
//            }",
//                    ],
                ]);
                ?>
            </li>
            <li class="sa-filters__item">
                <div class="sa-filters__item-title">Дата оновлення</div>
                <?php
                echo DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'modified_at',
                    'id' => 'filter_modified_at',
                    'useWithAddon' => true,
//                    'containerOptions' => [
//                        'class' => 'date-filter-container',
//                        'style' => 'width: 100%',
//                    ],
                    'language' => 'uk-UA',
                    'hideInput' => false,
                    'presetDropdown' => true,
                    'convertFormat' => true,
                    'value' => $searchModel->modified_at,
                    'pluginOptions' => [
                        'format' => 'dd.MM.yyyy',
                        'separator' => '-',
                        'opens' => 'left',
                        'autoUpdateInput' => true,
                    ],
//                    'pluginEvents' => [
//                        'apply.daterangepicker' => "function(ev, picker) {
//                $('#task-filter-form').submit(); // Отправка формы при выборе даты
//            }",
//                    ],
                ]);
                ?>
            </li>

        </ul>



        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = <<< JS
    $('#task-filter-form input, #task-filter-form select').on('change', function(e) {
        e.preventDefault();
        
        var serializedForm = $(this).closest('form').serialize();
        var newUrl = window.location.pathname + '?' + serializedForm;

        $.ajax({
            url: newUrl,
            type: 'GET',
            data: serializedForm,
            success: function (data) {
                history.pushState(null, '', newUrl);
                $.pjax.reload({
                    container: '#crud-datatable-pjax', // id контейнера GridView с Pjax
                    url: newUrl,
                    replace: false,
                    timeout: 10000
                });
            },
            error: function () {
                console.log('Error occurred while filtering tasks.');
            }
        });
    });
JS;
$this->registerJs($script);
?>
