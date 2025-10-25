<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ActOfWorkDetail $model */

$this->title = 'Create Act Of Work Detail';
$this->params['breadcrumbs'][] = ['label' => 'Act Of Work Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="act-of-work-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
