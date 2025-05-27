<?php

use kartik\bs5dropdown\ButtonDropdown;
use yii\bootstrap4\Dropdown;
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
$statusName = '~не визначено~';
$statusKey = null;

foreach (\common\models\Timer::$statusList as $k => $st) {
    $statusName = $st;
    $statusKey = $k;

    $html = <<<HTML
    <h3> Зміна статусу таймерів</h3> 
    <p>Ви впевнені, що хочете змінити статус вибраних <br> таймерів на <b style="color: red">{$statusName}</b>?</p>
HTML;

    if ($k == \common\models\Timer::STATUS_INVOICE) {
        $html .= "<h4 style='color: red; text-align: center'>Увага! дані трекери будуть скопійовані в акти</h4>";
        $html .= '<br>';
        $html .= '<p>Потрібно заповнити всі поля</p>';
        $html .= Html::label('Період тип', 'period_type', ['class' => 'form-label']);
        $html .= Html::dropDownList('period_type', 'first_half_month', \common\models\ActOfWork::$periodTypeList,
        ['class' => 'form-select', 'id' => 'period_type', 'required' => true, 'prompt' => 'Виберіть період']);
        $html .= '<br>';
        $html .= Html::label('Період місяць', 'period_mount', ['class' => 'form-label']);
        $html .= Html::dropDownList('period_mount', date('F', strtotime('-1 mounts')),
            \common\models\ActOfWork::$monthsList,
            ['class' => 'form-select', 'id' => 'period_mount', 'prompt' => 'Виберіть місяць']);

        $html .= '<br>';
        $html .= Html::label('Період рік', 'period_year', ['class' => 'form-label']);
        $html .= Html::dropDownList('period_year', date('Y'),
            array_combine(
                range(date('Y', strtotime('-1 year')), date('Y', strtotime('+1 year'))),
                range(date('Y', strtotime('-1 year')), date('Y', strtotime('+1 year')))
            ),
            ['class' => 'form-select', 'id' => 'period_year', 'prompt' => 'Виберіть рік']
        );
        $html .= '<br>';
        // коментар до поля
        $html .= Html::label('Коментар', 'comment', ['class' => 'form-label']);
        $html .= Html::textarea('comment', '', [
            'class' => 'form-control',
            'id' => 'comment',
            'placeholder' => 'Коментар до звітності (необов\'язково)',
            'rows' => 3,
            'required' => true
        ]);
    }



    $list[] = [
        'label' => $st,
        'url' => ['update-status', 'status' => $k, 'date_report' => null],
        'linkOptions' => [
            'role' => 'modal-remote-bulk',
            'data-confirm' => false, 'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-confirm-title' => 'Ви впевнені?',
            'data-confirm-message' => $html,

        ],
        'encode' => false
    ];
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
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'showPageSummary' => true,
                            'toolbar' => [
                                [
                                    'content' =>
                                        Html::a('<i class="fas fa-plus"></i>', ['create'], [
                                            'role' => 'modal-remote',
                                            'title' => 'Створити Timers',
                                            'class' => 'btn btn-success',
                                        ]) . '   ' .
                                        Html::a('<i class="fas fa-redo"></i>', [''], [
                                            'data-pjax' => 1,
                                            'class' => 'btn btn-default',
                                            'title' => 'Оновити таблицю',
                                        ]). '   ' .
                                        Html::a('<i class="fas fa-filter"></i>', ['advanced-filter'], [
                                            'role' => 'modal-remote',
                                            'title' => 'Розширені фільтри',
                                            'class' => 'btn btn-info',
                                        ]). '   ',
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
                                            '<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-sm btn-primary">
                                             <i class="fa-solid fa-cogs"></i> Змінити статус <b class="caret"></b>
                                             </a>' . Dropdown::widget(['items' => $list])
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
//    "size" => Modal::SIZE_EXTRA_LARGE,
//    "scrollable" => true,
//    "options" => [
//        "data-bs-backdrop" => "static",
//        "class" => "modal-dialog-scrollable",
//    ],
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
