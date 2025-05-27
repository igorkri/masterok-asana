<?php

use common\models\ActOfWorkDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\ActOfWorkDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Act Of Work Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="act-of-work-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Act Of Work Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'act_of_work_id',
            'time_id:datetime',
            'task_id',
            'project_id',
            //'project',
            //'task',
            //'description:ntext',
            //'amount',
            //'hours',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ActOfWorkDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
