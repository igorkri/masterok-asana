<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ActOfWork $model */

$this->title = 'Update Act Of Work: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Act Of Works', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="act-of-work-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
