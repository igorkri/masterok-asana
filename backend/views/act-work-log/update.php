<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ActWorkLog */


$this->title = 'Редагування : ';
$this->params['breadcrumbs'][] = ['label' => 'Список', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 1, 'url' => ['view', 'id' => 1]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="act-work-log-update">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
