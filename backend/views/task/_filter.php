<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>


    <div class="sa-layout__sidebar">
        <div class="sa-layout__sidebar-header">
            <div class="sa-layout__sidebar-title">
                Фільтри
            </div>
            <button type="button" class="sa-close sa-layout__sidebar-close" aria-label="Close"
                    data-sa-layout-sidebar-close=""></button>
        </div>
        <div class="sa-layout__sidebar-body sa-filters">
            <ul class="sa-filters__list">
                <!--            <li class="sa-filters__item">-->
                <!--                <div class="sa-filters__item-title">Price</div>-->
                <!--                <div class="sa-filters__item-body">-->
                <!--                    <div class="sa-filter-range" data-min="0" data-max="2000" data-from="0" data-to="2000">-->
                <!--                        <div class="sa-filter-range__slider"></div>-->
                <!--                        <input type="hidden" value="0" class="sa-filter-range__input-from" />-->
                <!--                        <input type="hidden" value="2000" class="sa-filter-range__input-to" />-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </li>-->
                <?php if (!$project): ?>
                    <li class="sa-filters__item">
                        <div class="sa-filters__item-title">Проєкти</div>
                        <div class="sa-filters__item-body">
                            <ul class="list-unstyled m-0 mt-n2">
                                <?php foreach (\common\models\Project::find()->orderBy('name ASC')->all() as $project): ?>
                                    <li>
                                        <label class="d-flex align-items-center pt-2">
                                            <input type="checkbox" name="TaskSearch[project_gid][]"
                                                   value="<?= Html::encode($project->gid) ?>"
                                                   class="form-check-input m-0 me-3 fs-exact-16 project-filter"/>
                                            <?= Html::encode($project->getName()) ?>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                <li class="sa-filters__item">
                    <div class="sa-filters__item-title">Виконавець</div>
                    <div class="sa-filters__item-body">
                        <ul class="list-unstyled m-0 mt-n2">
                            <?php foreach (\common\models\Task::find()->select('assignee_name')->distinct()->orderBy('assignee_name ASC')->all() as $assignee): ?>
                                <?php if (!$assignee->assignee_name || $assignee->assignee_name == 'Private User') continue; ?>
                                <li>
                                    <label class="d-flex align-items-center pt-2">
                                        <input type="checkbox" name="TaskSearch[assignee_name][]"
                                               value="<?= Html::encode($assignee->assignee_name) ?>"
                                               class="form-check-input m-0 me-3 fs-exact-16 assignee-filter"/>
                                        <?= Html::encode($assignee->assignee_name) ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>


            </ul>
        </div>
    </div>

<?php

$js = <<<JS
      
$(document).ready(function () {
    // Обработка изменений состояния любого input внутри блока фильтров
    $('.sa-filters input').on('change', function () {
        let selectedProjects = [];
        let selectedAssignees = [];
        let otherFilters = {};

        // Получаем значения всех выбранных проектов
        $('.project-filter:checked').each(function () {
            selectedProjects.push($(this).val());
        });

        // Получаем значения всех выбранных исполнителей
        $('.assignee-filter:checked').each(function () {
            selectedAssignees.push($(this).val());
        });

        // Также можно добавить обработку других типов input, например текстовые поля
        $('.sa-filters input[type="text"]').each(function () {
            let key = $(this).attr('name');
            let value = $(this).val();
            if (value) {
                otherFilters[key] = value;
            }
        });

        // Формируем параметры для URL
        let params = new URLSearchParams(window.location.search);

        // Добавляем проекты в параметры
        if (selectedProjects.length) {
            params.set('TaskSearch[project_gid]', selectedProjects.join(','));
        } else {
            params.delete('TaskSearch[project_gid]');
        }

        // Добавляем исполнителей в параметры
        if (selectedAssignees.length) {
            params.set('TaskSearch[assignee_name]', selectedAssignees.join(','));
        } else {
            params.delete('TaskSearch[assignee_name]');
        }

        // Добавляем другие фильтры
        for (let key in otherFilters) {
            if (otherFilters.hasOwnProperty(key)) {
                params.set(key, otherFilters[key]);
            }
        }

        // Обновляем URL без перезагрузки страницы
        let newUrl = window.location.pathname + '?' + params.toString();
        window.history.pushState({ path: newUrl }, '', newUrl);

        // Отправляем AJAX-запрос
        $.ajax({
            url: window.location.pathname, // Используем текущий URL, например /task/filter
            type: 'GET',
            data: {
                'TaskSearch[project_gid]': selectedProjects,
                'TaskSearch[assignee_name]': selectedAssignees,
                ...otherFilters // Добавляем другие фильтры
            },
            success: function (response) {
                // Предполагаем, что ответ содержит HTML с обновленным списком задач
                $('#task-list').html(response);
            },
            error: function () {
                alert('Ошибка при загрузке данных. Попробуйте еще раз.');
            }
        });
    });
});
    
JS;

$this->registerJs($js);