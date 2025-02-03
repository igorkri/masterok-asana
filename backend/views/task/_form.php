<?php

use igorkri\ckeditor\CKEditor;
use kartik\date\DatePicker;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use igorkri\elfinder\ElFinder;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Задача: ' . $model->name;
?>

<!-- sa-app__body -->
<div id="top" class="sa-app__body">
    <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
        <div class="container">
            <div class="py-5">
                <div class="row g-4 align-items-center">
                    <div class="col">
                        <nav class="mb-2" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-sa-simple">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item"><a
                                            href="<?= yii\helpers\Url::to(['index', 'project_gid' => $model->project_gid]) ?>">Таски</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?=Html::a('Таск в asana', $model->permalink_url, ['target' => 'blank'])?>
                                </li>
                            </ol>
                        </nav>
                        <h1 class="h3 m-0">Задача: <?= Html::encode($model->name) ?> </h1>
                    </div>
                    <div class="col-auto d-flex">
                        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary', 'id' => 'send-form']) ?>
                    </div>
                </div>
            </div>

            <div class="progress" style="--sa-progress--value: 0%; display: none">
                <div
                        class="
            progress-bar
            progress-bar-sa-primary
            progress-bar-striped
            progress-bar-animated
        "
                        role="progressbar"
                        aria-valuenow="25"
                        aria-valuemin="0"
                        aria-valuemax="100"
                ></div>
            </div>

            <div class="sa-page-meta mb-5">
                <?php if (!$model->isNewRecord): ?>
                    <div class="sa-page-meta__body">
                        <div class="sa-page-meta__list">
                            <div class="sa-page-meta__item">
                                <?= $model->project->name ?? '' ?>
                            </div>
                            <div class="sa-page-meta__item">
                                Створено: <?= Yii::$app->formatter->asDatetime($model->created_at, 'medium') ?></div>
                            <div class="sa-page-meta__item">
                                Оновлено: <?= Yii::$app->formatter->asDatetime($model->modified_at, 'medium') ?></div>
                            <div class="sa-page-meta__item d-flex align-items-center fs-6">
                                <span class="badge badge-sa-<?= $model->getPriority2()['color'] ?> me-2"><?= Html::encode($model->getPriority2()['name']) ?></span>
                                <span class="badge badge-sa-<?= $model->getType2()['color'] ?> me-2"><?= Html::encode($model->getType2()['name']) ?></span>
                            </div>
                            <?php if($model->task_sync === \common\models\Task::CRON_STATUS_STOP): ?>
                                <div class="sa-page-meta__item">
                                    <span class="badge badge-sa-danger">Задача не синхронізована статус: <?=$model->task_sync?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="sa-entity-layout"
                 data-sa-container-query='{"920":"sa-entity-layout--size--md","1100":"sa-entity-layout--size--lg"}'>
                <?php $form = ActiveForm::begin([
                    'id' => 'task-form',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'validateOnBlur' => false,
                    'validateOnChange' => false,
                    'validateOnType' => false,
                    'validateOnSubmit' => true,
                ]); ?>
                <div class="sa-entity-layout__body">
                    <div class="sa-entity-layout__main">
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="mb-5"><h2 class="mb-0 fs-exact-18">Основна інформація</h2></div>
                                <div class="mb-4">
                                    <?= $form->field($model, 'name')->textarea(['rows' => 2, 'class' => 'form-control']) ?>
                                </div>

                                <div class="mb-4">
                                    <?= $form->field($model, 'notes')->textarea(['rows' => 8]) ?>
                                </div>
                                <?php if (!$model->isNewRecord): ?>
                                    <div>
                                        <?php echo $form->field($model, 'work_done')->widget(CKEditor::class, [
                                            'id' => 'work-done',
                                            'editorOptions' =>
                                                ElFinder::ckeditorOptions('elfinder', [
                                                    'preset' => 'custom',
                                                    'height' => 200,
                                                    'language' => 'uk',
                                                    'controller' => 'elfinder',
                                                ]),
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!$model->isNewRecord): ?>
                            <?php if ($model->subTasks): ?>
                                <?= $this->render('sub-task', [
                                    'model' => $model
                                ]) ?>
                            <?php endif; ?>
                            <?php if ($model->attachments): ?>
                                <?= $this->render('_attachments', [
                                    'model' => $model
                                ]) ?>
                            <?php endif; ?>
                            <div class="card w-100 mt-5">
                                <div class="card-body p-5">
                                    <?= $this->render('_chat', [
                                        'model' => $model
                                    ]) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="sa-entity-layout__sidebar">
                        <?php if (!$model->isNewRecord): ?>
                            <div class="card w-100">
                                <div class="card-body d-flex align-items-center justify-content-between pb-0 pt-4">
                                    <h2 class="fs-exact-16 mb-0">Таймер</h2>
                                    <?= Html::a("Детальніше", '#', [
                                        'title' => '',
                                        'class' => 'pull-left detail-button',
//                                    'style' => 'margin-right: 20px; font-size:22px; color:#35b5f4',
                                        'data-bs-toggle' => "offcanvas",
                                        'data-bs-target' => "#offcanvasSms",
                                        'aria-controls' => "offcanvasSms",
                                        'data-bs-html' => "true"
                                    ]); ?>
                                </div>

                                <div class="card-body d-flex align-items-center pt-4">
                                    <div class="ms-3 ps-2">
                                        <div class="fs-exact-14 fw-medium">К-ть
                                            тайменгів <?= $model->getTimersCount() ?></div>
                                        <div class="mt-1">
                                            <h3>
                                                <div id="display">00:00:00</div>
                                            </h3>
                                            <button type="button" class="btn btn-success btn-sm"
                                                    data-status="<?= \common\models\Timer::STATUS_PROCESS ?>"
                                                    id="startButton">Старт
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                    data-status="<?= \common\models\Timer::STATUS_PROCESS ?>"
                                                    id="pauseButton">Пауза
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    data-status="<?= \common\models\Timer::STATUS_WAIT ?>"
                                                    id="stopButton">Стоп
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>


                        <div class="card w-100 mt-5">
                            <div class="card-body p-5">
                                <div class="mb-5"><h2 class="mb-0 fs-exact-18">Статус</h2></div>
                                <div class="mb-4">
                                    <?= $form->field($model, 'section_project_gid')->radioList(
                                        $model->getStatusList(),
                                        [
                                            'itemOptions' => ['class' => 'form-check-input'], // Добавляем CSS-класс для input
                                            'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                                                if ($model->isNewRecord) {
                                                    $checked = $index === 0;
                                                }
                                                return '<div class="form-check">' .
                                                    Html::radio($name, $checked, [
                                                        'value' => $value,
                                                        'class' => 'form-check-input', // Класс для input
                                                        'id' => $name . '_' . $index, // Уникальный id для каждого input
                                                    ]) .
                                                    Html::label($label, $name . '_' . $index, ['class' => 'form-check-label']) . // Класс для label
                                                    '</div>';
                                            },
                                        ]
                                    )->label(false) ?>

                                </div>
                            </div>
                        </div>

                        <div class="card w-100 mt-5">
                            <div class="card-body p-5">
                                <div class="mb-5"><h2 class="mb-0 fs-exact-18">Виконавець задачі</h2></div>
                                <?= $form->field($model, 'assignee_gid')
                                    ->dropDownList($model->getAssigneeList(), [
                                        'prompt' => 'Виберіть виконавця',
                                        'class' => 'form-select'
                                    ])->label(false) ?>
                            </div>
                        </div>
                        <div class="card w-100 mt-5">
                            <div class="card-body p-5">
                                <div class="mb-5"><h2 class="mb-0 fs-exact-18">Приоритет</h2></div>
                                <?= $form->field($model, 'priority')
                                    ->dropDownList($model->getPriorityList(), [
                                        'prompt' => 'Виберіть приоритет',
                                        'class' => 'form-select'
                                    ])->label(false) ?>
                            </div>
                            <div class="card-body p-5">
                                <div class="mb-5"><h2 class="mb-0 fs-exact-18">Тип задачі</h2></div>
                                <?= $form->field($model, 'type')
                                    ->dropDownList($model->getTypeList(), [
                                        'prompt' => 'Виберіть тип задачі',
                                        'class' => 'form-select'
                                    ])->label(false) ?>
                            </div>
                            <div class="card-body p-5">
                                <?= $form->field($model, 'start_on')->widget(
                                    DatePicker::class,
                                    [
                                        'options' => ['placeholder' => 'Оберіть дату ...'],
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd',
                                            'todayHighlight' => true,
                                        ]
                                    ]
                                ) ?>
                            </div>
                            <div class="card-body p-5">
                                <?= $form->field($model, 'due_on')->widget(
                                    DatePicker::class,
                                    [
                                        'options' => ['placeholder' => 'Оберіть дату ...'],
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd',
                                            'todayHighlight' => true,
                                        ]
                                    ]
                                ) ?>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center">
                                <div class="sa-divider my-5"></div>
                                <div class="w-100">
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Час, план.</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getTimePlan() ?>хв.
                                        </dd>
                                    </dl>
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Час, факт.</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getTimeFact() ?>хв.
                                        </dd>
                                    </dl>
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Час, рахунок.</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getTimeBill() ?>хв.
                                        </dd>
                                    </dl>
                                    <div class="sa-divider my-5"></div>
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Оплата (замовник)</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getPaymentCustomer() ?></dd>
                                    </dl>
                                    <dl class="list-unstyled m-0 mt-4">
                                        <dt class="fs-exact-14 fw-medium">Оплата (фахівець)</dt>
                                        <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $model->getPaymentSpecialist() ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<!-- sa-app__body / end -->
<?php echo $this->render('_timer', ['model' => $model, 'timers' => $timers]) ?>
<?php
$this->registerJs(<<<JS
    function init() {
        window.stroyka.containerQuery = (function() {
            const containerQuery = {
                init: function(element) {},
            };

            if (!window.ResizeObserver) {
                return containerQuery;
            }

            const ro = new ResizeObserver(function(entries) {
                const tasks = [];

                entries.forEach(function(entry) {
                    const breakpoints = JSON.parse(entry.target.dataset.saContainerQuery);
                    const mode = entry.target.dataset.saContainerQueryMode || 'all'; // all, bigger

                    if (!['all', 'bigger'].includes(mode)) {
                        throw Error('Undefined mode: ' + mode);
                    }

                    const sortFn = function(a, b) { return b - a; };

                    const add = [];
                    const remove = [];

                    Object.keys(breakpoints).map(parseFloat).sort(sortFn).forEach(function(width) {
                        let elementWidth = 0;

                        if (entry.borderBoxSize) {
                            const borderBoxSize = Array.isArray(entry.borderBoxSize) ? entry.borderBoxSize[0] : entry.borderBoxSize;

                            elementWidth = borderBoxSize.inlineSize;
                        } else {
                            elementWidth = entry.target.getBoundingClientRect().width;
                        }

                        if (elementWidth >= width
                            && (mode !== 'bigger' || add.length === 0)
                        ) {
                            add.push(breakpoints[width]);
                        } else {
                            remove.push(breakpoints[width]);
                        }
                    });

                    tasks.push(function() {
                        entry.target.classList.remove.apply(entry.target.classList, remove);
                        entry.target.classList.add.apply(entry.target.classList, add);
                    });
                });

                setTimeout(function() {
                    tasks.forEach(function(task) {
                        task();
                    });
                }, 0);
            });

            containerQuery.init = function(element) {
                ro.observe(element);
            };

            $('[data-sa-container-query]').each(function() {
                containerQuery.init(this);
            });

            return containerQuery;
        })();
    }

    $(document).on('click', '#send-form', function (e) {
        $('.progress').show();
        // Заполняем прогресс-бар
        $('.progress-bar').css('--sa-progress--value', '70%');
       
        e.preventDefault();
        var form = $('#task-form');
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (data) {
                if (data.success) {
                    $('.progress-bar').css('--sa-progress--value', '100%');
                    $('#live-toast').removeClass('toast-sa-dark').addClass(data.toast.class);
                    $('.toast #toast-name').text(data.toast.name);
                    $('.toast .toast-body').html(data.toast.message);
                    $('.toast').toast('show');
                    $('#top').html(data.html);
                    init();
                } else {
                    $('#live-toast').removeClass('toast-sa-dark').addClass(data.toast.class);
                    $('.toast #toast-name').text(data.toast.name);
                    $('.toast .toast-body').html(data.toast.message);
                    $('.toast').toast('show');
                }
            },
            error: function () {
                toastr.error('Помилка запиту');
            }
        });
    });



    // ---------------------------------------- таймер ----------------------------------------
   let timerInterval;
    let totalSeconds = 0;
    let isPaused = false;
    let emoji = '🟥'; // Стоп
    
    // Обновление отображения таймера
    function updateTimerDisplay() {
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
    
        const formattedTime = [
            hours.toString().padStart(2, '0'),
            minutes.toString().padStart(2, '0'),
            seconds.toString().padStart(2, '0')
        ].join(':');
    
        document.title = emoji + ' ' + formattedTime;
        $('#display').text(formattedTime);
    
        if (emoji === '🔴') {
            document.title = isPaused ? '⏸ ' + formattedTime : '🔴 ' + formattedTime;
        }
        
        //отправляем данные на сервер каждую минуту
        if (totalSeconds % 60 === 0) {
            sendTimerData($('#startButton').data('status'));
        }
    }
    
    // Отправка данных таймера на сервер
    function sendTimerData(status) {
        const taskId = $('#task-id').data('task-id');
        $.ajax({
            url: 'timer',
            type: 'POST',
            data: {
                task_id: taskId,
                totalSeconds: totalSeconds,
                status: status
            },
            success: function (data) {
                if (data.success) {
                    toastr.success(data.toast.message);
                } else {
                    toastr.error('Ошибка: ' + data.message);
                }
            },
            error: function () {
                toastr.error('Ошибка запроса.');
            }
        });
    }
    
    // Запуск таймера
    function startTimer() {
        isPaused = false;
        emoji = '🔴'; // Старт
        sendTimerData($('#startButton').data('status'));
        timerInterval = setInterval(() => {
            totalSeconds++;
            updateTimerDisplay();
        }, 1000);
    }
    
    // Пауза таймера
    function pauseTimer() {
        isPaused = true;
        emoji = '⏸'; // Пауза
        clearInterval(timerInterval); // Останавливаем таймер
        sendTimerData($('#pauseButton').data('status'));
        updateTimerDisplay();
    }
    
    // Остановка таймера
    function stopTimer() {
        isPaused = false;
        emoji = '🟥'; // Стоп
        clearInterval(timerInterval);
        sendTimerData($('#stopButton').data('status'));
        totalSeconds = 0;
        updateTimerDisplay();
    }
    
    // Инициализация таймера
    function initializeTimer() {
        const taskId = $('#task-id').data('task-id');
        $.ajax({
            url: 'get-timer', // Укажите правильный маршрут для получения таймера
            type: 'GET',
            data: { task_id: taskId },
            success: function (data) {
                if (data.success) {
                    totalSeconds = data.totalSeconds || 0; // Устанавливаем начальное значение
                    updateTimerDisplay();
                } else {
                    totalSeconds = 0; // Сбрасываем на ноль, если записи нет
                    updateTimerDisplay();
                }
            },
            error: function () {
                toastr.error('Ошибка при инициализации таймера.');
                totalSeconds = 0; // Начинаем с нуля в случае ошибки
                updateTimerDisplay();
            }
        });
    }
    
    $(document).ready(function () {
        initializeTimer(); // Инициализация таймера при загрузке страницы
    
        $('#startButton').on('click', function () {
            clearInterval(timerInterval);
            startTimer();
        });
    
        $('#pauseButton').on('click', function () {
            pauseTimer();
        });
    
        $('#stopButton').on('click', function () {
            stopTimer();
        });
    });

JS
);
?>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "size" => Modal::SIZE_EXTRA_LARGE,
//    "scrollable" => true,
//    "options" => [
//        "data-bs-backdrop" => "static",
//        // "class" => "modal-dialog-scrollable",
//    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>


