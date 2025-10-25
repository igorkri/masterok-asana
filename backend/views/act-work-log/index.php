<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use kartik\grid\GridView;
use igorkri\ajaxcrud\CrudAsset; 
use igorkri\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ActWorkLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Act Work Logs';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="container">
    <div class="act-work-log-index">
        <div id="ajaxCrudDatatable">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => require(__DIR__.'/_columns.php'),
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="fas fa-plus"></i>', ['create'],
                        ['role'=>'modal-remote','title'=> 'Створити Act Work Logs','class'=>'btn btn-default']).
                        Html::a('<i class="fas fa-redo"></i>', [''],
                        ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Оновити таблицю']).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,          
                'panel' => [
                    'type' => 'primary', 
                    'heading' => '<i class="fas fa-list"></i> Act Work Logs список',
                    //'before'=>'<em>* Змінюйте розмір стовпців таблиці так само, як у електронній таблиці, перетягуючи краї стовпців.</em>',
                    'after'=>BulkButtonWidget::widget([
                                'buttons'=>Html::a('<i class="far fa-trash-alt"></i>&nbsp; Видалити',
                                    ["bulkdelete"] ,
                                    [
                                        "class"=>"btn btn-danger btn-xs",
                                        'role'=>'modal-remote-bulk',
                                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                        'data-request-method'=>'post',
                                        'data-confirm-title'=>'Ви впевнені?',
                                        'data-confirm-message'=>'Ви впевнені, що хочете видалити цей елемент?'
                                    ]),
                            ]).                        
                            '<div class="clearfix"></div>',
                ]
            ])?>
        </div>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    //"size" => Modal::SIZE_EXTRA_LARGE,
//    "scrollable" => true,
//    "options" => [
//        "data-bs-backdrop" => "static",
//        // "class" => "modal-dialog-scrollable",
//    ],
    "footer"=>"", // always need it for jquery plugin
])?>
<?php Modal::end(); ?>
