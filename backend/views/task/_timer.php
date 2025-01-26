<?php

use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/* @var $model common\models\Task */
/* @var $timers common\models\Timer[] */
/* @var $timer common\models\Timer */

?>
<div style="width: 75%;" class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSms"
     aria-labelledby="offcanvasSmsLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasSmsLabel">Список таймінгів</h5>
        <button type="button" class="sa-close sa-close--modal" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?php Pjax::begin(['id' => 'crud-datatable-pjax']) ?>
        <?=Html::a('+', ['create-track', 'task_id' => Yii::$app->request->get('gid')],
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
                <th scope="col">Ціна</th>
                <th scope="col">Коментар</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            $totalHours = 0;
            $totalMinutes = 0;
            $totalSeconds = 0;
            $total_minute = 0;
            $sum = 0;
            $sum_track = 0;
            if (isset($timers)){
                foreach ($timers as $timer):
                    // Разбиваем строку времени на часы, минуты и секунды
                    list($hours, $minutes, $seconds) = explode(":", $timer->time);
                    // Суммируем общее время
                    $totalHours += (int)$hours;
                    $totalMinutes += (int)$minutes;
                    $totalSeconds += (int)$seconds;
                    $total_minute += $timer->minute;
                    // Рассчитываем сумму с учетом коэффициента
                    $sum_track = ($timer->minute / 60) * 400 * ($timer->coefficient > 0 ? $timer->coefficient : 1);
                    $sum += $sum_track;
                    ?>
                    <tr>
                        <th scope="row"><?=Html::a($i, ['/timer/update', 'id' => $timer->id], [
                                'class' => 'text-reset', 'data-pjax' => 1,
                                'role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'
                            ]) ?></th>
                        <td><?= Yii::$app->formatter->asDatetime($timer->created_at, 'medium') ?></td>
                        <td><?= $timer->getStatusText($timer->status) ?></td>
                        <td><?= $timer->time ?></td>
                        <td width="120"><?= $timer->minute ?> (<?=round($timer->minute / 60, 2)?>)</td>
                        <td><?= $timer->coefficient ?></td>
                        <td width="120"><?= Yii::$app->formatter->asDecimal($sum_track, 2) ?></td>
                        <td><?= $timer->comment ?></td>
                    </tr>

                    <?php $i++; endforeach;
                // Преобразуем секунды и минуты в часы, если необходимо
                $totalMinutes += floor($totalSeconds / 60);
                $totalSeconds %= 60;
                $totalHours += floor($totalMinutes / 60);
                $totalMinutes %= 60;
            }
            ?>
            <tr style="background: #b4b1b1">
                <th colspan=3>Загальні дані:</th>
                <th><?= sprintf("%02d:%02d:%02d", $totalHours, $totalMinutes, $totalSeconds); ?></th>
                <th width="120"><?= $total_minute ?> (<?=round($total_minute / 60, 2)?>)</th>
                <th></th>
                <th width="120"><?=Yii::$app->formatter->asDecimal($sum, 2) ?> грн.</th>
                <th></th>
            </tr>
            </tbody>

        </table>
        <?php Pjax::end() ?>
    </div>
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