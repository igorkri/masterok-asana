<?php

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
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // 'width' => '5%',
    // 'vAlign' => GridView::ALIGN_MIDDLE,
    // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'payer_id',
        'value' => 'payer.name',
        'label' => 'Платник',
        'filter' => \yii\helpers\ArrayHelper::map(\common\models\report\Payer::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'invoice_no',
         'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'act_no',
         'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'date_act',
        'visible' => $page_type === \common\models\report\Invoice::PAGE_TYPE_ACT || $page_type == \common\models\report\Invoice::PAGE_TYPE_ALL,
        'width' => '5%',
        'format' => ['date', 'php:d.m.Y'],
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
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
            ],
        ],
        'filterInputOptions' => ['placeholder' => 'Виберіть дату'],
        'value' => function ($model) {
            return $model->date_act ?: '';
        },
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],[
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'date_invoice',
        'visible' => $page_type === \common\models\report\Invoice::PAGE_TYPE_INVOICE || $page_type == \common\models\report\Invoice::PAGE_TYPE_ALL,
        'width' => '5%',
        'format' => ['date', 'php:d.m.Y'],
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
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
            ],
        ],
        'filterInputOptions' => ['placeholder' => 'Виберіть дату'],
        'value' => function ($model) {
            return $model->date_invoice ?: '';
        },
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'title_invoice',
        'visible' => $page_type === \common\models\report\Invoice::PAGE_TYPE_INVOICE || $page_type == \common\models\report\Invoice::PAGE_TYPE_ALL,
        'label' => 'Назва',
        'value' => function ($model) {
            return $model->title_invoice ?: '';

        },
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'visible' => $page_type === \common\models\report\Invoice::PAGE_TYPE_ACT || $page_type == \common\models\report\Invoice::PAGE_TYPE_ALL,
        'attribute' => 'title_act',
        'label' => 'Назва',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'qty',
        'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'amount',
        'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
        'pageSummary' => function ($summary, $data, $widget) {
            return Yii::$app->formatter->asDecimal($summary, 2);
        },
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'created_at',
    // 'width' => '5%',
    // 'vAlign' => GridView::ALIGN_MIDDLE,
    // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'updated_at',
    // 'width' => '5%',
    // 'vAlign' => GridView::ALIGN_MIDDLE,
    // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {delete}',
        'width' => '180px',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Детальніше', 'data-toggle' => 'tooltip', 'class' => 'btn btn-sm btn-primary'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Редагувати', 'data-toggle' => 'tooltip', 'class' => 'btn btn-sm btn-info'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Видалити', 'class' => 'btn btn-sm btn-danger',
            'data-confirm' => false, 'data-method' => false,
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Ви впевнені?',
            'data-confirm-message' => 'Ви впевнені, що хочете видалити?'],
    ],

];   