<?php

use common\models\Project;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\ProjectSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Проєкти';
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
            <?php //$this->render('_filter')?>
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
                            <div class="col">
                                <input
                                        type="text"
                                        placeholder="Пошук"
                                        class="form-control form-control--search mx-auto"
                                        id="table-search"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="sa-divider"></div>
                    <table class="sa-datatables-init" data-order='[[ 1, "asc" ]]' data-sa-search-input="#table-search">
                        <thead>
                        <tr>
                            <th class="w-min" data-orderable="false">
                                <input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." />
                            </th>
                            <th class="min-w-20x">Назва</th>
                            <th>К-ть тасків</th>
                            <th>Таки до роботи</th>
<!--                            <th>Хв</th>-->
                            <th class="w-min" data-orderable="false"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dataProvider->getModels() as $model): ?>
                        <tr>
                            <td><input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." /></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <a href="app-product.html" class="text-reset"><?=$model->name?></a>
                                    </div>
                                </div>
                            </td>
                            <td><a href="app-category.html" class="text-reset"><?=$model->getTaskQty()?></a></td>
                            <td><?=$model->getTaskExecution()?></td>
<!--                            <td>-->
<!--                                <div class="sa-price">-->
<!--                                    <span class="sa-price__symbol">$</span>-->
<!--                                    <span class="sa-price__integer">749</span>-->
<!--                                    <span class="sa-price__decimal">.00</span>-->
<!--                                </div>-->
<!--                            </td>-->
                            <td>
                                <div class="dropdown">
                                    <button
                                            class="btn btn-sa-muted btn-sm"
                                            type="button"
                                            id="product-context-menu-0"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-label="More"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                            <path
                                                    d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"
                                            ></path>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="product-context-menu-0">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item" href="#">Duplicate</a></li>
                                        <li><a class="dropdown-item" href="#">Add tag</a></li>
                                        <li><a class="dropdown-item" href="#">Remove tag</a></li>
                                        <li><hr class="dropdown-divider" /></li>
                                        <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
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
                   first: 'Перша',
                   last: 'Остання',
                   previous: 'Назад',
                   next: 'Вперед',
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