<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Task */


$this->title = 'Редагування : ';
$this->params['breadcrumbs'][] = ['label' => 'Список', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 1, 'url' => ['view', 'id' => 1]];
$this->params['breadcrumbs'][] = $this->title;
?>

        <?= $this->render('_form', [
            'model' => $model,
            'timers' => $timers,
        ]) ?>

<?php
$this->registerJsFile('@web/vendor/bootstrap/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
