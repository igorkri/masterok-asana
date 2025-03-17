<?php


?>


<h3>Успішно створено <?=$type_doc?>!</h3>
<p>Ви можете завантажити за посиланням:</p>
<?php echo \yii\helpers\Html::a('Завантажити', $path, ['class' => 'btn btn-success', 'download' => true]) ?>
<hr>
<?php echo $path ?>
