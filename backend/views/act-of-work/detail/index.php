<?php

use common\models\ActOfWorkDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

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
            'amount',
            'hours',
            //'created_at',
            //'updated_at',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, ActOfWorkDetail $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
        ],
    ]); ?>
    </div>