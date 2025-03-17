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
        'attribute'=>'name',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'email',
//        // 'width' => '5%',
//        'vAlign' => GridView::ALIGN_MIDDLE,
//        'hAlign' => GridView::ALIGN_CENTER,
//    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'phone',
//        // 'width' => '5%',
//        'vAlign' => GridView::ALIGN_MIDDLE,
//        'hAlign' => GridView::ALIGN_CENTER,
//    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'contract',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'director',
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'director_case',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'requisites',
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
        'width' => '180px',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Детальніше','data-toggle'=>'tooltip', 'class'=>'btn btn-primary'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Редагувати', 'data-toggle'=>'tooltip', 'class'=>'btn btn-info'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Видалити', 'class'=>'btn btn-danger',
                          'data-confirm'=>false, 'data-method'=>false,
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Ви впевнені?',
                          'data-confirm-message'=>'Ви впевнені, що хочете видалити?'], 
    ],

];   