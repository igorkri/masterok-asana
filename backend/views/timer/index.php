<?php

use kartik\bs5dropdown\ButtonDropdown;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use kartik\grid\GridView;
use igorkri\ajaxcrud\CrudAsset;
use igorkri\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\TimerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Timers';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
$list = [];
foreach (\common\models\Timer::$statusList as $k => $st) {
    $list[] = [
        'label' => $st,
        'url' => ['update-status', 'status' => $k],
        'linkOptions' => [
            'role' => 'modal-remote-bulk',
            'data-confirm' => false, 'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-confirm-title' => 'Ви впевнені?',
            'data-confirm-message' => 'Ви впевнені, що хочете змінити статус?'
        ],
        'encode' => false
    ];
}

//debugDie($list);
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
            <?php //echo $this->render('_filter-project', ['project' => $project, 'searchModel' => $searchModel]) ?>
            <div class="sa-layout__content">
                <div class="card">
                    <div id="ajaxCrudDatatable">

                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'showPageSummary' => true,
                            'toolbar' => [
                                [
                                    'content' =>
                                        Html::a('<i class="fas fa-plus"></i>', ['create'], [
                                            'role' => 'modal-remote',
                                            'title' => 'Створити Timers',
                                            'class' => 'btn btn-success',
                                        ]) .
                                        Html::a('<i class="fas fa-redo"></i>', [''], [
                                            'data-pjax' => 1,
                                            'class' => 'btn btn-default',
                                            'title' => 'Оновити таблицю',
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
                                            Html::a('<i class="fa-solid fa-file-excel"></i> Згенерувати EXCEL',
                                                ["export-excel"],
                                                [
                                                    "class" => "btn btn-sm btn-info",
                                                    'role' => 'modal-remote-bulk',
                                                    'data-confirm' => false, 'data-method' => false,// for overide yii data api
                                                    'data-request-method' => 'post',
                                                    'data-confirm-title' => 'Ви впевнені?',
                                                    'data-confirm-message' => 'Ви впевнені, що хочете згенерувати EXCEL?'
                                                ])
                                            . ' ' .
                                            ButtonDropdown::widget([
                                                'label' => '<i class="fa-solid fa-cogs"></i> Змінити статус',
                                                'encodeLabel' => false,
                                                'buttonOptions' => ['class' => 'btn btn-sm btn-primary'],
                                                'dropdown' => [
                                                    'items' => $list
                                                ],
                                            ])
//                                            Html::a('Згенерувати акти',
//                                                ["generate-acts"],
//                                                [
//                                                    "class" => "btn btn-sm btn-success btn-xs",
//                                                    'role' => 'modal-remote-bulk',
//                                                    'data-confirm' => false, 'data-method' => false,// for overide yii data api
//                                                    'data-request-method' => 'post',
//                                                    'data-confirm-title' => 'Ви впевнені?',
//                                                    'data-confirm-message' => 'Ви впевнені, що хочете згенерувати акти?'
//                                                ])
//                                            . ' ' .
//                                            Html::a('Згенерувати акти та рахунки',
//                                                ["generate-acts-invoices"],
//                                                [
//                                                    "class" => "btn btn-sm btn-success btn-xs",
//                                                    'role' => 'modal-remote-bulk',
//                                                    'data-confirm' => false, 'data-method' => false,// for overide yii data api
//                                                    'data-request-method' => 'post',
//                                                    'data-confirm-title' => 'Ви впевнені?',
//                                                    'data-confirm-message' => 'Ви впевнені, що хочете згенерувати акти та рахунки?'
//                                                ]),
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
    "scrollable" => true,
    "options" => [
        "data-bs-backdrop" => "static",
        "class" => "modal-dialog-scrollable",
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php $css = <<<CSS
.select2-container .select2-selection--single {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  height: auto;
  user-select: none;
  -webkit-user-select: none;

}

hr {
    margin: 1px;
}
CSS;

$this->registerCss($css);
