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
        'attribute'=>'act_of_work_id',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'act_of_work_detail_id',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'timer_id',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'task_id',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'project_id',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
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