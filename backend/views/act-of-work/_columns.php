<?php

use common\models\ActOfWork;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

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
    [
        'class' => '\kartik\grid\DataColumn',
        'headerOptions' => ['class' => 'text-nowrap', 'style' => 'text-align: center;'],
        'attribute' => 'number',
        'format' => 'raw',
        'value' => function ($model) {
            return $model->number;
        },
        'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'headerOptions' => ['class' => 'text-nowrap', 'style' => 'text-align: center;'],
        'attribute' => 'status',
        'filter' => ActOfWork::$statusList,
        'width' => '10%',
        'value' => function ($model) {
            return ActOfWork::$statusList[$model->status] ?? '⸺';
        },
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'headerOptions' => ['class' => 'text-nowrap', 'style' => 'text-align: center;'],
        'attribute' => 'period',
        'format' => 'raw',
        'value' => function ($model) {
            // json ["first_half_month","May","2025"]
            if (!$model->period) {
                return '⸺';
            }
            $period = json_decode($model->period, true);
            if (is_array($period) && count($period) === 3) {
                $month = ActOfWork::$monthsList[$period[1]] ?? $period[1];
                $firstHalf = ActOfWork::$periodTypeList[$period[0]] ?? $period[0];
                return "{$month} {$period[2]} ({$firstHalf})";
            }
        },
//        'width' => '20%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
    //                    'user_id',
//                            'date',
    //'description:ntext',
//    'total_amount',
    [
        'class' => '\kartik\grid\DataColumn',
        'headerOptions' => ['class' => 'text-nowrap', 'style' => 'text-align: center;'],
        'label' => 'Сума',
        'attribute' => 'total_amount',
        'format' => ['decimal', 2],
        'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_RIGHT,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'headerOptions' => ['class' => 'text-nowrap', 'style' => 'text-align: center;'],
        'label' => 'Сплачено',
        'attribute' => 'paid_amount',
        'format' => ['decimal', 2],
        'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_RIGHT,
    ],
     [
         'class' => '\kartik\grid\DataColumn',
         'headerOptions' => ['class' => 'text-nowrap', 'style' => 'text-align: center;'],
        'attribute' => 'file_excel',
         'filter' => false,
        'format' => 'raw',
        'value' => function ($model) {
            //http://masterok-asana.loc/report/time/Звіт_2025-05-27.xlsx
            if ($model->file_excel) {
                return Html::a(
                    '<i class="fas fa-file-excel"></i> ' . basename($model->file_excel),
                    $model->file_excel,
                    ['target' => '_blank', 'title' => 'Завантажити Excel файл', 'data-pjax' => 0]
                );
            }
            return '⸺';
        },
        'width' => '11%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
     ],
      [
        'class' => '\kartik\grid\DataColumn',
        'headerOptions' => ['class' => 'text-nowrap', 'style' => 'text-align: center;'],
        'attribute' => 'created_at',
        'format' => ['datetime', 'php:d.m.Y H:i:s'],
        'width' => '10%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
      ],
    [
        'class' => '\kartik\grid\DataColumn',
        'headerOptions' => ['class' => 'text-nowrap', 'style' => 'text-align: center;'],
        'attribute' => 'updated_at',
        'format' => ['datetime', 'php:d.m.Y H:i:s'],
        'width' => '10%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'width' => '150px',
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