<?php

use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\grid\GridView;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '40px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    ],
//     [
//     'class'=>'\kartik\grid\DataColumn',
//     'attribute'=>'id',
//         'value' => function ($model) {
//             return $model->taskG->project->name;
//         },
//     'width' => '5%',
//     'vAlign' => GridView::ALIGN_MIDDLE,
//     'hAlign' => GridView::ALIGN_CENTER,
//     ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'task_gid',
        'format' => 'raw',
        'value' => function ($model) {
            if (!$model->taskG) {
                return '⸺';
            }
            $projectName = $model->taskG->project->name ?? '⸺';
            // Основное значение
            $taskLink = \yii\helpers\Html::a(
                $model->taskG->name,
                Url::to(['/task/update', 'gid' => $model->task_gid]),
                ['data-pjax' => 0, 'target' => '_blank']
            );

            // Описание для строки
            $description = \yii\helpers\Html::tag(
                'div',
                $model->comment, // Поле "description" или другое с текстом
                ['class' => 'row-description', 'style' => 'font-size: 12px; color: #666; margin-top: 5px;']
            );

            return "<div class='text-info'>" . $projectName . "</div>" . "<hr>" . $taskLink . "<hr>" . $description; // Основное значение + описание
        },
        'width' => '30%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'time',
        'format' => 'raw',
        'width' => '7%',
        'pageSummary' => function ($summary, $data, $widget) {
            return \common\models\Timer::getTime($data);
        },
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'minute',
//        // 'width' => '5%',
//        'vAlign' => GridView::ALIGN_MIDDLE,
//        'hAlign' => GridView::ALIGN_CENTER,
//        'pageSummary' => true,
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'coefficient',
        'filter' => \common\models\Timer::$coefficientList,
        'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'price',
        'pageSummary' => function ($summary, $data, $widget) {
            return Yii::$app->formatter->asDecimal($summary, 2);
        },
        'value' => function ($model) {
            return $model->getCalcPrice();
        },
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'format' => 'raw',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Оберіть статус'],
            'data' => \common\models\Timer::$statusList,
            'pluginOptions' => [
//                'multiple' => true,
                'theme' => Select2::THEME_DEFAULT,
                'width' => '100%',
                'allowClear' => true,
                'hideSearch' => true, 
            ],
        ],
        'value' => function ($model) {
            return $model->status_act == \common\models\Timer::STATUS_ACT_OK
                ? '<span class="text-success">Актовано</span>'
                : \common\models\Timer::$statusList[$model->status];
        },
        'width' => '10%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'created_at',
//        'format' => ['date', 'php:d.m.Y'],
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'pluginOptions' => array_merge([], [
                'locale' => ['format' => 'DD.MM.YYYY'],
                'autoUpdateInput' => false,
                'opens' => 'left',
                'showDropdowns' => true,
                'ranges' => [
                    "Сьогодні" => ["moment().startOf('day')", "moment()"],
                    "Вчора" => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                    "Цей тиждень" => ["moment().startOf('week')", "moment().endOf('week')"],
                    "Цей місяць" => ["moment().startOf('month')", "moment().endOf('month')"],
                    "Минулий місяць" => ["moment().startOf('month').subtract(1,'month')", "moment().endOf('month').subtract(1,'month')"],
                    "Цей рік" => ["moment().startOf('year')", "moment().endOf('year')"],
                    "Минулий рік" => ["moment().startOf('year').subtract(1,'year')", "moment().endOf('year').subtract(1,'year')"],
                ],
            ]),
        ],
        'filterInputOptions' => ['placeholder' => 'Виберіть дату'],
        'value' => function ($model) {
            return Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y') ?: '⸺';
        },
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
//        'format' => ['date', 'php:d.m.Y'],
        'format' => 'raw',
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'pluginOptions' => array_merge([], [
                'locale' => ['format' => 'DD.MM.YYYY'],
                'autoUpdateInput' => false,
                'opens' => 'left',
                'showDropdowns' => true,
                'ranges' => [
                    "Сьогодні" => ["moment().startOf('day')", "moment()"],
                    "Вчора" => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                    "Цей тиждень" => ["moment().startOf('week')", "moment().endOf('week')"],
                    "Цей місяць" => ["moment().startOf('month')", "moment().endOf('month')"],
                    "Минулий місяць" => ["moment().startOf('month').subtract(1,'month')", "moment().endOf('month').subtract(1,'month')"],
                    "Цей рік" => ["moment().startOf('year')", "moment().endOf('year')"],
                    "Минулий рік" => ["moment().startOf('year').subtract(1,'year')", "moment().endOf('year')"],
                ],
            ]),
        ],
        'filterInputOptions' => ['placeholder' => 'Виберіть дату'],
        'value' => function ($model) {
            return $model->updated_at ? Yii::$app->formatter->asDate($model->updated_at, 'php:d.m.Y') : '⸺';
        },
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {delete}',
        'buttons' => [
            'view' => function ($url, $model, $key) {
                return \yii\helpers\Html::a('<span class="fas fa-eye"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Детальніше',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-sm btn-primary',
                ]);
            },
            'update' => function ($url, $model, $key) {
                if ($model->status_act == \common\models\Timer::STATUS_ACT_OK) {
                    return '';
                }
                return \yii\helpers\Html::a('<span class="fas fa-pencil-alt"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Редагувати',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-sm btn-info',
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return \yii\helpers\Html::a('<span class="fas fa-trash"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Видалити',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-sm btn-danger',
                    'data-confirm' => false,
                    'data-method' => false,
                    // for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Ви впевнені?',
                    'data-confirm-message' => 'Ви впевнені, що хочете видалити?',
                ]);
            },
        ],
        'vAlign' => 'middle',
        'width' => '180px',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Детальніше', 'data-toggle' => 'tooltip', 'class' => 'btn btn-sm btn-primary'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Редагувати', 'data-toggle' => 'tooltip', 'class' => 'btn btn-sm btn-info'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Видалити', 'class' => 'btn btn-sm btn-danger',
            'data-confirm' => false, 'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Ви впевнені?',
            'data-confirm-message' => 'Ви впевнені, що хочете видалити?'],
    ],

];   