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
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // 'width' => '5%',
    // 'vAlign' => GridView::ALIGN_MIDDLE,
    // 'hAlign' => GridView::ALIGN_CENTER,
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'task_gid',
        'format' => 'raw',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Оберіть задачу'],
            'pluginOptions' => [
                'theme' => Select2::THEME_DEFAULT,
                'width' => '100%',
                'allowClear' => true,
                'minimumInputLength' => count(\common\models\Task::getTaskGids()) > 20 ? 3 : 0,
            ],
            'data' => \yii\helpers\ArrayHelper::map(\common\models\Task::find()->where(['gid' => \common\models\Task::getTaskGids()])->all(), 'gid', 'name'),
        ],
        'value' => function ($model) {
            return \yii\helpers\Html::a($model->taskG->name, Url::to(['/task/update', 'gid' => $model->task_gid]), ['data-pjax' => 0, 'target' => '_blank']);
        },
        'width' => '25%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_LEFT,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'time',
        'format' => 'raw',
        'width' => '7%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'minute',
//        // 'width' => '5%',
//        'vAlign' => GridView::ALIGN_MIDDLE,
//        'hAlign' => GridView::ALIGN_CENTER,
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
        'attribute' => 'comment',
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
            return \common\models\Timer::$statusList[$model->status];
        },
        'width' => '10%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'created_at',
        'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'width' => '5%',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Детальніше', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Редагувати', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Видалити',
            'data-confirm' => false, 'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Ви впевнені?',
            'data-confirm-message' => 'Ви впевнені, що хочете видалити?'],
    ],

];   