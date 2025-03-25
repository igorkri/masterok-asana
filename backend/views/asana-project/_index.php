<?php

use common\components\Modal;
use common\models\Project;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;


/** @var yii\web\View $this */
/** @var backend\models\search\ProjectSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="top" class="sa-app__body">
    <div class="mx-xxl-3 px-4 px-sm-5">
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="<?= Url::to(['/site/index']) ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0"><?= $this->title ?></h1>
                </div>
                <div class="col-auto d-flex">
                    <!--                    <a href="#" class="btn btn-secondary me-3">Import</a>-->
                    <a href="<?=Url::to(['create'])?>" class="btn btn-secondary me-3">New local task</a>
                    <a href="<?=Url::to(['create'])?>" class="btn btn-primary">New task</a>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-xxl-3 px-4 px-sm-5 pb-6">
        <div class="sa-layout">
            <div class="sa-layout__backdrop" data-sa-layout-sidebar-close=""></div>
            <?php //echo $this->render('_filter-project', ['project' => $project, 'searchModel' => $searchModel]) ?>
            <div class="sa-layout__content">
                <div class="card">
                    <div id="ajaxCrudDatatable">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'width' => '30px',
                // 'width' => '5%',
                // 'vAlign' => GridView::ALIGN_MIDDLE,
                // 'hAlign' => GridView::ALIGN_CENTER,
            ],
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute' => 'id',
                'format' => 'raw',
                'width' => '50px',
                 'vAlign' => GridView::ALIGN_MIDDLE,
                 'hAlign' => GridView::ALIGN_CENTER,
            ],
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    /* @var $model \common\models\Task */
                    return $model->name;
                },
                //'width' => '50px',
                 'vAlign' => GridView::ALIGN_MIDDLE,
                 'hAlign' => GridView::ALIGN_LEFT,
            ],
//            'gid',
//            'workspace_gid',
//            'resource_type',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Project $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
//    "size" => Modal::SIZE_EXTRA_LARGE,
//    "scrollable" => true,
//    "options" => [
//        "data-bs-backdrop" => "static",
//        // "class" => "modal-dialog-scrollable",
//    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

