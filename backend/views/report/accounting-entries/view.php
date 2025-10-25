<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AccountingEntries */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Accounting Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container">
    <div class="accounting-entries-view">
    
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
            'number',
            'counterparty',
            'debit',
            'credit',
            'description',
            'document_at',
            'created_at',
            ],
        ]) ?>

    </div>
</div>
