<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\report\Payer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Payers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container">
    <div class="payer-view">
    
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
            'name',
            'email:email',
            'phone',
            'contract',
            'director',
            'director_case',
            'requisites:html',
            'created_at',
            ],
        ]) ?>

    </div>
</div>
