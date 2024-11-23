<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>


<div class="sa-layout__sidebar">
    <div class="sa-layout__sidebar-header">
        <div class="sa-layout__sidebar-title">
            Фільтри
        </div>
        <button type="button" class="sa-close sa-layout__sidebar-close" aria-label="Close" data-sa-layout-sidebar-close=""></button>
    </div>
    <div class="sa-layout__sidebar-body sa-filters">
        <ul class="sa-filters__list">
            <li class="sa-filters__item">
                <div class="sa-filters__item-title">Виконавець</div>
                <div class="sa-filters__item-body">
                    <ul class="list-unstyled m-0 mt-n2">
                        <?php foreach (\common\models\Task::find()->select('assignee_name')->where(['project_gid' => $project->gid])->distinct()->orderBy('assignee_name ASC')->all() as $assignee): ?>
                            <?php if (!$assignee->assignee_name || $assignee->assignee_name == 'Private User') continue; ?>
                            <li>
                                <label class="d-flex align-items-center pt-2">
                                    <input type="checkbox" name="TaskSearch[assignee_name]"
                                           value="<?= Html::encode($assignee->assignee_name) ?>"
                                           class="form-check-input m-0 me-3 fs-exact-16 assignee-filter"/>
                                    <?= Html::encode($assignee->assignee_name) ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
            <li class="sa-filters__item">
                <div class="sa-filters__item-title">Статус</div>
                <div class="sa-filters__item-body">
                    <ul class="list-unstyled m-0 mt-n2">
                        <?php foreach (\common\models\Task::find()->select('section_project_name')->where(['project_gid' => $project->gid])->distinct()->all() as $section): ?>
                            <li>
                                <label class="d-flex align-items-center pt-2">
                                    <input type="checkbox" name="TaskSearch[section_project_name]"
                                           value="<?= Html::encode($section->section_project_name) ?>"
                                           class="form-check-input m-0 me-3 fs-exact-16 section-filter"/>
                                    <?= Html::encode($section->section_project_name) ?>
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
        let filterParams = new URLSearchParams();

        // Проходимся по всем input элементам внутри блока фильтров
        $('.sa-filters input').each(function () {
            let name = $(this).attr('name');
            let value = $(this).val();

            if ($(this).attr('type') === 'checkbox' && $(this).is(':checked')) {
                filterParams.append(name, value);
            }
        });

        // Обновляем URL без перезагрузки страницы
        let newUrl = window.location.pathname + '?' + filterParams.toString();
        window.history.pushState({ path: newUrl }, '', newUrl);

        // Отправляем Pjax-запрос для обновления данных на странице
        $.pjax.reload({
            container: '#crud-datatable-pjax', // id контейнера GridView с Pjax
            url: newUrl,
            replace: false,
            timeout: 10000,
        });
    });
});

JS;

$this->registerJs($js);
?>



