<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\ActOfWork $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Act Of Works', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="act-of-work-view">

    <?php if (!Yii::$app->request->isAjax): ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'number',
            'status',
            'period',
            'user_id',
            'date',
            'description:ntext',
            'total_amount',
            'paid_amount',
            'file_excel',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
