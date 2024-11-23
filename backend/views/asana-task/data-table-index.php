<?php

use common\models\Project;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\ProjectSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Project $project */

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
                                <li class="breadcrumb-item"><a href="<?=Url::to(['/site/index'])?>">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?=$this->title?></li>
                            </ol>
                        </nav>
                        <h1 class="h3 m-0"><?=$this->title?></h1>
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
                <?php echo $this->render($project ? '_filter-project' : '_filter', ['project' => $project, 'searchModel' => $searchModel])?>
                <div class="sa-layout__content">
                    <div class="card">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'id',
                                'name',
                                'gid',
                                [
                                    'class' => ActionColumn::className(),
                                    'urlCreator' => function ($action, \common\models\Task $model, $key, $index, $column) {
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

<?php

$js = <<<JS
    (function() {
       $.fn.DataTable.ext.pager.numbers_length = 5;
       $.extend($.fn.DataTable.defaults, {
           pagingType: 'full_numbers', // Тип пагинации
           pageLength: 20, // Количество строк на одной странице
           lengthMenu: [30, 50, 100], // Опции выбора количества строк
           language: {
               paginate: {
                   // first: 'Перша',
                   // last: 'Остання',
                   previous: 'Назад',
                   next: 'Вперед',
                   first: false,
                   last: false,
                   // previous: false,
                   // next: false,
               },
               info: 'Показано записи з _START_ по _END_ із _TOTAL_',
               lengthMenu: 'Рядків на сторінці: _MENU_',
           },
       });



       const template = '' +
           '<"sa-datatables"' +
               '<"sa-datatables__table"t>' +
               '<"sa-datatables__footer"' +
                   '<"sa-datatables__pagination"p>' +
                   '<"sa-datatables__controls"' +
                       '<"sa-datatables__legend"i>' +
                       '<"sa-datatables__divider">' +
                       '<"sa-datatables__page-size"l>' +
                   '>' +
               '>' +
           '>';

       $('.sa-datatables-init').each(function() {
           const tableSearchSelector = $(this).data('sa-search-input');
           const table = $(this).DataTable({
               dom: template,
               paging: true,
               ordering: true,
               drawCallback: function() {
                   $(this.api().table().container()).find('.pagination').addClass('pagination-sm');
               },
           });

           if (tableSearchSelector) {
               $(tableSearchSelector).on('input', function() {
                   table.search(this.value).draw();
               });
           }
       });
   })();


JS;

$this->registerJs($js);