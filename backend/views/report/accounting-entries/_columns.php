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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'number',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'counterparty',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'debit',
        // 'width' => '5%',
        'pageSummary' => function ($summary, $data, $widget) {
            return Yii::$app->formatter->asDecimal($summary, 2);
        },
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'credit',
        // 'width' => '5%',
        'pageSummary' => function ($summary, $data, $widget) {
            return Yii::$app->formatter->asDecimal($summary, 2);
        },
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'description',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'document_at',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Детальніше','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Редагувати', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Видалити', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Ви впевнені?',
                          'data-confirm-message'=>'Ви впевнені, що хочете видалити?'], 
    ],

];   