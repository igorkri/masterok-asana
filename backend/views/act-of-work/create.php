<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ActOfWork $model */

$this->title = 'Create Act Of Work';
$this->params['breadcrumbs'][] = ['label' => 'Act Of Works', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="act-of-work-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => null,
        'dataProvider' => null,
    ]) ?>

</div>
