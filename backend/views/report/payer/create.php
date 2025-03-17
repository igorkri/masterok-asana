<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\report\Payer */

$this->title = 'Створення нового запису';
$this->params['breadcrumbs'][] = ['label' => 'Список', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container">
    <div class="payer-create">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
