<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ActOfWorkDetail $model */

$this->title = 'Update Act Of Work Detail: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Act Of Work Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="act-of-work-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
