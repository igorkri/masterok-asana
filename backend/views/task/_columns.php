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
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'gid',
//        'format' => 'raw',
//        'value' => function($model) {
//            /* @var $model \common\models\Task */
////            debugDie($model->customFields);
//            foreach ($model->customFields as $customField) {
//                if ($customField->name == 'Приоритет') {
//                    return '<a href="' . Url::to(['task/view', 'id' => $model->id]) . '" class="text-reset
//                    text-decoration-none">' . $customField->display_value . '</a>';
//                }
//            }
////            return '<a href="' . Url::to(['task/view', 'id' => $model->id]) . '" class="text-reset
////                    text-decoration-none">' . $model->gid . '</a>';
//
//        },
//        // 'width' => '5%',
//        'vAlign' => GridView::ALIGN_MIDDLE,
//        'hAlign' => GridView::ALIGN_CENTER,
//    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
        'format' => 'raw',
        'value' => function($model) {
            /* @var $model \common\models\Task */
            return $model->getNameGrid();
        },
        // 'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'assignee_gid',
//        // 'width' => '5%',
//        'vAlign' => GridView::ALIGN_MIDDLE,
//        'hAlign' => GridView::ALIGN_CENTER,
//    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'assignee_name',
//        // 'width' => '5%',
//        'vAlign' => GridView::ALIGN_MIDDLE,
//        'hAlign' => GridView::ALIGN_CENTER,
//    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'assignee_status',
//        // 'width' => '5%',
//        'vAlign' => GridView::ALIGN_MIDDLE,
//        'hAlign' => GridView::ALIGN_CENTER,
//    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'section_project_gid',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'section_project_gid',
         'label' => 'Час, факт.',
            'filter' => false,
         'value' => function($model) {
             return $model->getRealTime() ?? 0;
         },
         'width' => '5%',
         'vAlign' => GridView::ALIGN_MIDDLE,
         'hAlign' => GridView::ALIGN_CENTER,
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'section_project_gid',
         'label' => 'Статус',
         'filter' => false,
         'format' => 'raw',
            'value' => function($model) {
                return '<a class="text-reset"><div class="badge badge-sa-' . $model->getStatusColor(). '">' . $model->getStatus(). '</div></a>';
            },
         'width' => '5%',
         'vAlign' => GridView::ALIGN_MIDDLE,
         'hAlign' => GridView::ALIGN_CENTER,
     ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'completed',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'completed_at',
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
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'due_on',
         'filter' => false,
         'value' => function($model) {
             return $model->getDueOn();
         },
         'width' => '5%',
         'vAlign' => GridView::ALIGN_MIDDLE,
         'hAlign' => GridView::ALIGN_CENTER,
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'start_on',
         'filter' => false,
            'value' => function($model) {
                return $model->getStartOn();
            },
         'width' => '5%',
         'vAlign' => GridView::ALIGN_MIDDLE,
         'hAlign' => GridView::ALIGN_CENTER,
     ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'notes',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'permalink_url',
         'filter' => false,
         'label' => '',
         'format' => 'raw',
         'value' => function($model) {
             return '<a href="' . $model->permalink_url . '" title="Переглянути в asana" class="text-reset text-decoration-none" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>';
         },
         'width' => '5%',
         'vAlign' => GridView::ALIGN_MIDDLE,
         'hAlign' => GridView::ALIGN_CENTER,
     ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'project_gid',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'workspace_gid',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'modified_at',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'resource_subtype',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'num_hearts',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'num_likes',
        // 'width' => '5%',
        // 'vAlign' => GridView::ALIGN_MIDDLE,
        // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
//    [
//        'class' => 'kartik\grid\ActionColumn',
//        'dropdown' => false,
//        'vAlign'=>'middle',
//        'urlCreator' => function($action, $model, $key, $index) {
//                return Url::to([$action,'id'=>$key]);
//        },
//        'viewOptions'=>['role'=>'modal-remote','title'=>'Детальніше','data-toggle'=>'tooltip'],
//        'updateOptions'=>['role'=>'modal-remote','title'=>'Редагувати', 'data-toggle'=>'tooltip'],
//        'deleteOptions'=>['role'=>'modal-remote','title'=>'Видалити',
//                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
//                          'data-request-method'=>'post',
//                          'data-toggle'=>'tooltip',
//                          'data-confirm-title'=>'Ви впевнені?',
//                          'data-confirm-message'=>'Ви впевнені, що хочете видалити?'],
//    ],

];   