<?php

use common\models\Timer;
use yii\bootstrap5\Html;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;

/* @var $model common\models\Task */
/* @var $timers common\models\Timer[] */
/* @var $timer common\models\Timer */


$total_minute = 0;
$total_price = 0;
?>

    <div class="offcanvas-body">
        <?php Pjax::begin(['id' => 'crud-datatable-pjax']) ?>
        <?=Html::a('+', ['/timer/create', 'task_id' => $model->gid],
            [
                'class' => 'btn btn-success',
                'data-pjax' => 1,
                'role'=>'modal-remote','title'=>'Create', 'data-toggle'=>'tooltip'
            ]) ?>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Дата</th>
                <th scope="col">Статус</th>
                <th scope="col">Час</th>
                <th scope="col">Хвилини</th>
                <th scope="col">Коефіцієнт</th>
                <th scope="col">Коментар</th>
                <th scope="col">Статус акт</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            if (isset($timers)){
                foreach ($timers as $timer):
                    $total_minute += $timer->minute;
                    $total_price += $timer->getCalcPrice();
                    ?>
                    <tr>
                        <th scope="row">
                            <?php if ($timer->status_act != Timer::STATUS_ACT_OK): ?>
                            <?=Html::a($i, ['/timer/update', 'id' => $timer->id], [
                                'class' => 'text-reset', 'data-pjax' => 1,
                                'role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'
                            ]) ?>
                            <?php else: ?>
                                <?= $i ?>
                            <?php endif; ?>
                        </th>
                        <td><?= Yii::$app->formatter->asDatetime($timer->created_at, 'php:d.m.Y H:i:s') ?></td>
                        <td><?= $timer->getStatusText($timer->status) ?></td>
                        <td><?= $timer->time ?></td>
                        <td width="120"><?= $timer->minute ?> (<?=round($timer->getTimeHour(), 2)?>)</td>
                        <td><?= $timer->coefficient ?></td>
                        <td><?= $timer->comment ?></td>
                        <td><?= $timer->status_act == Timer::STATUS_ACT_OK ? 'Актовано' : 'Не актовано' ?></td>
                    </tr>
                    <?php $i++; endforeach;
            }
            ?>
            <tr style="background: #b4b1b1">
                <th colspan=3>Загальні дані:</th>
                <th><?= Timer::getTotalTime($total_minute) ?></th>
                <th width="120"><?= $total_minute ?> (<?= round($total_minute / 60, 2) ?>)</th>
                <th><?= round($total_price, 2) ?></th>
                <th></th>
            </tr>
            </tbody>

        </table>
        <?php Pjax::end() ?>
    </div>

<span id="task-id" data-task-id="<?= $model->gid ?>"></span>
<?php
$remainingSeconds = 0;
$js = <<<JS
$(document).ready(function () {
    
    // Обработчик события click на ссылку
$('a.btn-secondary').on('click', function(e) {
    e.preventDefault(); // Предотвращаем переход по ссылке по умолчанию

    // Отправка запроса AJAX
    $.ajax({
        url: $(this).attr('href'), // URL, указанный в атрибуте href ссылки
        type: 'GET', // Метод запроса (GET, POST и т.д.)
        dataType: 'json', // Тип данных, ожидаемых от сервера (json, html и т.д.)
        success: function(response) {
            // Код, выполняемый в случае успешного выполнения запроса
            console.log(response); // Выводим ответ от сервера в консоль
            // Здесь можно выполнить дополнительные действия в зависимости от ответа сервера
        },
        error: function(xhr, status, error) {
            // Код, выполняемый в случае ошибки выполнения запроса
            console.error(error); // Выводим сообщение об ошибке в консоль
            // Здесь можно выполнить дополнительные действия в случае ошибки
        }
    });
});
});


JS;

//$this->registerJs($js);

    ?>

