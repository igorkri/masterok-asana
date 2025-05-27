<?php

use common\models\ActOfWorkDetail;
use igorkri\ajaxcrud\BulkButtonWidget;
use kartik\grid\GridView;
use yii\helpers\Html;


/** @var yii\web\View $this */
/** @var backend\models\search\ActOfWorkDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'список таймів по акту виконаних робіт';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="card sa-card">

        <div class="card-header sa-card__header">
            <h3 class="card-title sa-card__title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools sa-card__tools">
            </div>
        </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'act_of_work_id',
//            'time_id:datetime',
//            'task_id',
//            'project_id',
            'project',
            'task',
            'description:ntext',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'amount',
                'format' => 'decimal',
                'value' => function ($model) {
                    return $model->amount ?? '0.00';
                },
                'pageSummary' => function ($summary, $data, $widget) {
                    return Yii::$app->formatter->asDecimal($summary);
                },
                // 'width' => '5%',
                'vAlign' => GridView::ALIGN_MIDDLE,
                'hAlign' => GridView::ALIGN_RIGHT,
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'hours',
                'format' => 'decimal',
                'pageSummary' => function ($summary, $data, $widget) {
                    return Yii::$app->formatter->asDecimal($summary);
                },
                'value' => function ($model) {
                    return $model->hours;
                },
                // 'width' => '5%',
                'vAlign' => GridView::ALIGN_MIDDLE,
                'hAlign' => GridView::ALIGN_RIGHT,
            ],
            //'created_at',
            //'updated_at',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, ActOfWorkDetail $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
        ],
        'showPageSummary' => true,
        'toolbar' => false,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'panel' => [
            'type' => 'dark',
            'heading' => '<i class="fas fa-list"></i> список',
            'after' => false,
        ],
    ]); ?>
    </div>