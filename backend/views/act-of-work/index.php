<?php

use common\components\CustomGridView;
use common\components\Modal;
use common\models\ActOfWork;
use igorkri\ajaxcrud\BulkButtonWidget;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;


/** @var yii\web\View $this */
/** @var backend\models\search\ActOfWorkSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Act Of Works';
$this->params['breadcrumbs'][] = $this->title;

$totlalSum = [
    'total_amount' => [],
    'paid_amount' => [],
];

foreach ($dataProvider->models as $model) {
    $totlalSum['total_amount'][] = $model->total_amount;
    $totlalSum['paid_amount'][] = $model->paid_amount;
}

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
                </div>
            </div>
        </div>
    </div>
    <div class="mx-xxl-3 px-4 px-sm-5 pb-6">
        <div class="sa-layout">
            <div class="sa-layout__backdrop" data-sa-layout-sidebar-close=""></div>
            <?php //echo $this->render('_advanced-filter', []) ?>
            <div class="sa-layout__content">
                <div class="card">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
//                            'showPageSummary' => true,
                            'rowOptions' => function ($model, $key, $index, $grid) {
                                return ['data-sortable-id' => $model->id];
                            },
                            'options' => [
                                'data' => [
                                    'sortable-widget' => 1,
                                    'sortable-url' => \yii\helpers\Url::toRoute(['/ajax/sorting', 'modelName' => ActOfWork::class]),
                                ]
                            ],
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' =>
                                        Html::a('<i class="fas fa-redo"></i>', [''], [
                                            'data-pjax' => 1,
                                            'class' => 'btn btn-default',
                                            'title' => 'Оновити таблицю',
                                        ]). '   '
//                                        Html::a('<i class="fas fa-filter"></i>', ['advanced-filter'], [
//                                            'role' => 'modal-remote',
//                                            'title' => 'Розширені фільтри',
//                                            'class' => 'btn btn-info',
//                                        ]). '   ',
                                        . Html::a('скинути сесію та відобразити всі', ['', 'bulk-hide' => 'clear'], [
                                            'title' => '',
                                            'class' => 'btn btn-info',
                                        ]),
                                ],
                                '{toggleData}',
//                                '{export}',
                            ],
                            'toggleDataOptions' => [
                                'all' => [
                                    'icon' => 'resize-full',
                                    'label' => 'Показати все',
                                    'class' => 'btn btn-primary',
                                    'title' => 'Показати все',
                                ],
                                'page' => [
                                    'icon' => 'resize-small',
                                    'label' => 'Показати по сторінках',
                                    'class' => 'btn btn-primary',
                                    'title' => 'Показати по сторінках',
                                ],
                            ],
                            'export' => [
                                'showConfirmAlert' => false, // Отключение предупреждения
                                'target' => GridView::TARGET_BLANK, // Открытие файла в новой вкладке
                                'label' => 'Швидкий експорт',
                                'options' => ['class' => 'btn btn-info'], // Настройка кнопки
                                // отключить выпадающий список
                                'dropdown' => false,
                                'icon' => 'fas fa-file-excel',

                            ],
                            'exportConfig' => [
                                GridView::EXCEL => [
                                    'label' => 'Експорт в Excel',
                                    'icon' => 'fas fa-file-excel',
                                    'iconOptions' => ['class' => 'text-success'],
                                    'showHeader' => true,
                                    'filename' => 'export_time_' . date('Y-m-d'),
                                    'alertMsg' => 'Файл буде збережено',
                                    'options' => ['title' => 'Microsoft Excel'],
                                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' => [
                                'type' => 'dark',
                                'before' => "Загальна сума: <b>" . Yii::$app->formatter->asCurrency(array_sum($totlalSum['total_amount'])) . "</b> | Оплачено: <b>" . Yii::$app->formatter->asCurrency(array_sum($totlalSum['paid_amount'])) . "</b>",
                                'heading' => '<i class="fas fa-list"></i> список',
                                'after' => BulkButtonWidget::widget([
                                        'buttons' => Html::a('<i class="far fa-trash-alt"></i>&nbsp; Видалити',
                                                ["bulkdelete"],
                                                [
                                                    "class" => "btn btn-sm btn-danger btn-xs",
                                                    'role' => 'modal-remote-bulk',
                                                    'data-confirm' => false, 'data-method' => false,// for overide yii data api
                                                    'data-request-method' => 'post',
                                                    'data-confirm-title' => 'Ви впевнені?',
                                                    'data-confirm-message' => 'Ви впевнені, що хочете видалити цей елемент?'
                                                ])
                                            . ' ' .
                                            Html::a(' Не відображати',
                                                ["", 'bulk-hide' => 1],
                                                [
                                                    "class" => "btn btn-sm btn-info",
                                                    'role' => 'modal-remote-bulk',
                                                    'data-confirm' => false, 'data-method' => false,// for overide yii data api
                                                    'data-request-method' => 'post',
                                                    'data-confirm-title' => 'Ви впевнені?',
                                                    'data-confirm-message' => 'Ви впевнені не відображати зі списку?'
                                                ])
                                            . ' ' .
                                            '<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-sm btn-primary">
                                             <i class="fa-solid fa-cogs"></i> Змінити статус <b class="caret"></b>
                                             </a>'
                                    ]) .
                                    '<div class="clearfix"></div>',
                            ],
                        ]) ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Modal::begin([
        "id" => "ajaxCrudModal",
        "size" => Modal::SIZE_EXTRA_LARGE,
//        "scrollable" => true,
//        "options" => [
//            "data-bs-backdrop" => "static",
//            "class" => "modal-dialog-scrollable",
//        ],
        "footer" => "", // always need it for jquery plugin
    ]) ?>
    <?php Modal::end(); ?>
