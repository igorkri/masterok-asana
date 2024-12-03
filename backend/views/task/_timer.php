<?php

use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/* @var $model common\models\Task */

?>
<div style="width: 75%;" class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSms"
     aria-labelledby="offcanvasSmsLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasSmsLabel">Список таймінгів</h5>
        <button type="button" class="sa-close sa-close--modal" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?php Pjax::begin(['id' => 'time-pjax']) ?>
        <?=Html::a('+', ['create-track', 'task_id' => Yii::$app->request->get('id')],
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
                        <th scope="row"><?=Html::a($i, ['update-track', 'id' => $timer->id], [
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
    
    function status1Time() {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: 'res-time-task',
                method: 'get',
                data: {
                    task_id: $('#task-id').data('task-id'),
                    status: 1
                },
            })
            .done(function(data) {
                resolve(data);
            })
            .fail(function(error) {
                reject(error);
            });
        });
    }
    
    status1Time().then(function(data) {
        let elapsedTimeInSeconds = data * 60; // Здесь можно использовать данные, полученные от сервера
        let stopwatchInterval;
        updateDisplay();
    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secondsPart = seconds % 60;
    
        const formattedHours = hours < 10 ? '0' + hours : hours;
        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        const formattedSeconds = secondsPart < 10 ? '0' + secondsPart : secondsPart;
        return formattedHours + ':' + formattedMinutes + ':' + formattedSeconds;
    }

    function updateDisplay() {
        const display = $('#display');
        const title = $('#head-title');
        if (display.length) {
            //console.log(elapsedTimeInSeconds);
            display.text(formatTime(elapsedTimeInSeconds));
            title.text(formatTime(elapsedTimeInSeconds));
            
        }
        var min = elapsedTimeInSeconds % 60;
        if(min === 59){
            stopStopwatch(1)
        }
        return elapsedTimeInSeconds;
    }

    function startStopwatch() {
        stopwatchInterval = setInterval(function () {
            elapsedTimeInSeconds++;
            updateDisplay();
        }, 1000);
    }


    function stopStopwatch(status = 0) {
    

    // Получить текущее значение счетчика
    var elapsedTimeInSeconds = getElapsedTimeInSeconds();

    // Отправить AJAX запрос
    $.ajax({
        url: 'it-add-task-time',
        // url: '/shop-admin/app/ajax/it-add-task-time',
        method: 'get',
        data: {
            task_id: $('#task-id').data('task-id'),
            time: formatTime(elapsedTimeInSeconds),
            status: status
        },
    })
    .done(function(data) {
        
        console.log(data);
        
        if(data.content !== 'update'){
            clearInterval(stopwatchInterval); // Останавливаем таймер
            $('.it-work-task-update').html(data.content);
            // Обнулить счетчик после успешной отправки
            resetStopwatch();
        }
    })
    .fail(function(error) {
        console.error('Ошибка при отправке AJAX запроса:', error);
    });
}

// Функция для паузы таймера
    function pauseStopwatch() {
        clearInterval(stopwatchInterval); // Останавливаем таймер
        // Получить текущее значение счетчика
    var elapsedTimeInSeconds = getElapsedTimeInSeconds();

    // Отправить AJAX запрос
    $.ajax({
        url: 'it-add-task-time',
        // url: '/shop-admin/app/ajax/it-add-task-time',
        method: 'get',
        data: {
            task_id: $('#task-id').data('task-id'),
            time: formatTime(elapsedTimeInSeconds),
            status: 1
        },
    })
    .done(function(data) {
        
    })
    .fail(function(error) {
    });
    }

    function getElapsedTimeInSeconds() {
        return elapsedTimeInSeconds;
    }

    function resetStopwatch() {
        // Обнулить счетчик и обновить дисплей
        elapsedTimeInSeconds = 0;
        updateDisplay();
    }
    $('#startButton').on('click', function() {
        startStopwatch(1);
    });

    $('#stopButton').on('click', function() {
        stopStopwatch(0);
    });
    
    $('#pauseButton').on('click', function() {
        pauseStopwatch(0);
    });
    }).catch(function(error) {
        console.error('Произошла ошибка:', error);
    });
  


    $('#process').fadeIn();
    $('#process').fadeOut();

     $('#form-application').on('change', function () {
         var form = $(this);
         var data = form.serialize();

         $.ajax({
             url: form.attr('action'),
             type: form.attr('method'),
             data: data,
             beforeSend: function () {
                 $('#process').fadeIn();
             }
         })
         .done(function (data) {
             console.log(data);
             if (data.success == 'true') {
                 $.toast({
                     loader: false,
                     hideAfter: 1000,
                     position: 'top-right',
                     text: 'Успешно сохранено!',
                     bgColor: '#00b52a',
                     textColor: 'white',
                     icon: 'success'
                 });
             } else {
                 $.each(data.content.errors, function (index, value) {
                     $.toast({
                         loader: true,
                         hideAfter: 5000,
                         position: 'top-right',
                         text: value,
                         bgColor: '#FF1356',
                         textColor: 'white',
                         icon: 'error'
                     });
                 });
             }
         })
         .fail(function () {
             $.each(data.content.errors, function (index, value) {
                 $.toast({
                     loader: true,
                     hideAfter: 5000,
                     position: 'top-right',
                     text: value,
                     bgColor: '#FF1356',
                     textColor: 'white',
                     icon: 'error'
                 });
             });
         })
         .always(function () {
             $('#process').fadeOut();
             // $.pjax.reload({ container: '#all-page' });
         });

         return false;
     })
     .on('submit', function (e) {
         e.preventDefault();
     });

    
});

JS;
$this->registerJs($js, \yii\web\View::POS_READY);

?>