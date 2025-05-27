<?php

use common\models\ActOfWork;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;


/** @var yii\web\View $this */
/** @var backend\models\search\ActOfWorkSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Act Of Works';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="top" class="sa-app__body">
    <div class="mx-xxl-3 px-4 px-sm-5">
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="<?=Url::to(['/site/index'])?>">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?=$this->title?></li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0"><?=$this->title?></h1>
                </div>
                <div class="col-auto d-flex">
<!--                    <a href="#" class="btn btn-secondary me-3">Import</a>-->
<!--                    <a href="app-product.html" class="btn btn-primary">New product</a>-->
                    <?= Html::a('Create Act Of Work', ['create'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-xxl-3 px-4 px-sm-5 pb-6">
        <div class="sa-layout">
            <div class="sa-layout__backdrop" data-sa-layout-sidebar-close=""></div>
            <?php echo $this->render( '_filter', ['searchModel' => $searchModel])?>
            <div class="sa-layout__content">
                <div class="card">
                    <div class="p-4">
                        <div class="row g-4">
                            <div class="col-auto sa-layout__filters-button">
                                <button class="btn btn-sa-muted btn-sa-icon fs-exact-16" data-sa-layout-sidebar-open="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor">
                                        <path
                                                d="M7,14v-2h9v2H7z M14,7h2v2h-2V7z M12.5,6C12.8,6,13,6.2,13,6.5v3c0,0.3-0.2,0.5-0.5,0.5h-2 C10.2,10,10,9.8,10,9.5v-3C10,6.2,10.2,6,10.5,6H12.5z M7,2h9v2H7V2z M5.5,5h-2C3.2,5,3,4.8,3,4.5v-3C3,1.2,3.2,1,3.5,1h2 C5.8,1,6,1.2,6,1.5v3C6,4.8,5.8,5,5.5,5z M0,2h2v2H0V2z M9,9H0V7h9V9z M2,14H0v-2h2V14z M3.5,11h2C5.8,11,6,11.2,6,11.5v3 C6,14.8,5.8,15,5.5,15h-2C3.2,15,3,14.8,3,14.5v-3C3,11.2,3.2,11,3.5,11z"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="sa-divider"></div>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
        //                    'id',
                            'number',
                            'status',
                            'period',
        //                    'user_id',
//                            'date',
                            //'description:ntext',
                            'total_amount',
                            'paid_amount',
                            //'file_excel',
        //                    'created_at',
        //                    'updated_at',
                            [
                                'class' => ActionColumn::class,
                                'urlCreator' => function ($action, ActOfWork $model, $key, $index, $column) {
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