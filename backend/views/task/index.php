<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use kartik\grid\GridView;
use igorkri\ajaxcrud\CrudAsset;
use igorkri\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Tasks';
//$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$this->title = 'Задачі' . ($project ? ': ' . $project->getName() : '');
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
                    <a href="#" class="btn btn-secondary me-3">Import</a>
                    <a href="app-product.html" class="btn btn-primary">New product</a>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-xxl-3 px-4 px-sm-5 pb-6">
        <div class="sa-layout">
            <div class="sa-layout__backdrop" data-sa-layout-sidebar-close=""></div>
            <?php echo $this->render('_filter-project', ['project' => $project, 'searchModel' => $searchModel]) ?>
            <div class="sa-layout__content">
                <div class="card">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                    Html::a('<i class="fas fa-plus"></i>', ['create'],
                                        ['role' => 'modal-remote', 'title' => 'Створити Tasks', 'class' => 'btn btn-warning']) .
                                    Html::a('<i class="fas fa-redo"></i>', ['', 'project_gid' => $project->gid],
                                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Оновити таблицю']) .
                                    '{toggleData}'
//                        '{export}'
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' => [
                                'type' => '',
                                'heading' => '',
                                //'before'=>'<em>* Змінюйте розмір стовпців таблиці так само, як у електронній таблиці, перетягуючи краї стовпців.</em>',
//                                'after' => BulkButtonWidget::widget([
//                                        'buttons' => Html::a('<i class="far fa-trash-alt"></i>&nbsp; Видалити',
//                                            ["bulkdelete"],
//                                            [
//                                                "class" => "btn btn-danger btn-xs",
//                                                'role' => 'modal-remote-bulk',
//                                                'data-confirm' => false, 'data-method' => false,// for overide yii data api
//                                                'data-request-method' => 'post',
//                                                'data-confirm-title' => 'Ви впевнені?',
//                                                'data-confirm-message' => 'Ви впевнені, що хочете видалити цей елемент?'
//                                            ]),
//                                    ]) .
                                    '<div class="clearfix"></div>',
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    //"size" => Modal::SIZE_EXTRA_LARGE,
//    "scrollable" => true,
//    "options" => [
//        "data-bs-backdrop" => "static",
//        // "class" => "modal-dialog-scrollable",
//    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>
