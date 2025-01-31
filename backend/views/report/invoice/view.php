<?php

use common\models\report\NumberFormatterUA;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\report\Invoice */

$this->title = $model->title_act;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

//debugDie(NumberFormatterUA::numberToString(15450.56));

?>


<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Деталі рахунку</h5>
        </div>
        <div class="row">
            <br>
            <div class="col-md-12"><h5>Платник: <?=$model->payer->name ?? ''?> </h5></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Кількість: <?=$model->qty?> <br>
            </div>
            <div class="col-md-6">
                Сума: <?=Yii::$app->formatter->asDecimal($model->amount, 2)?>
                <b><i>(<?=NumberFormatterUA::numberToString($model->amount)?>) </i></b>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Первая колонка -->
                <div class="col-md-6">
                    <?= \yii\widgets\DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-bordered table-striped'],
                        'attributes' => [
//                            [
//                                'attribute' => 'payer.name',
//                                'label' => 'Платник',
//                            ],
                            [
                                'attribute' => 'title_invoice',
                                'label' => 'Назва рахунку',
                            ],
                            [
                                'attribute' => 'invoice_no',
                                'label' => 'Номер рахунку',
                            ],
                            [
                                'attribute' => 'date_invoice',
                                'label' => 'Дата рахунку',
                                'format' => ['date', 'php:Y-m-d'],
                            ],
//                            [
//                                'attribute' => 'qty',
//                                'label' => 'Кількість',
//                            ],
//                            [
//                                'attribute' => 'amount',
//                                'label' => 'Сума',
//                                'format' => ['decimal', 2],
//                            ],
                        ],
                    ]) ?>
                </div>

                <!-- Вторая колонка -->
                <div class="col-md-6">
                    <?= \yii\widgets\DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-bordered table-striped'],
                        'attributes' => [

                            [
                                'attribute' => 'title_act',
                                'label' => 'Назва акту',
                            ],
                            [
                                'attribute' => 'act_no',
                                'label' => 'Номер акту',
                            ],
                            [
                                'attribute' => 'date_act',
                                'label' => 'Дата акту',
                                'format' => ['date', 'php:Y-m-d'],
                            ],
//                            [
//                                'attribute' => 'updated_at',
//                                'label' => 'Оновлено',
//                                'format' => ['datetime', 'php:Y-m-d H:i'],
//                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>


