<?php

namespace common\models;

use Asana\Client;
use console\controllers\AsanaController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $gid
 * @property string $parent_gid
 * @property string $name
 * @property string|null $assignee_gid
 * @property string|null $assignee_name
 * @property string|null $assignee_status
 * @property int|null $completed
 * @property string|null $completed_at
 * @property string|null $section_project_gid
 * @property string|null $section_project_name
 * @property string $created_at
 * @property string|null $due_on
 * @property string|null $start_on
 * @property string|null $notes
 * @property string|null $work_done
 * @property string|null $permalink_url
 * @property string|null $project_gid
 * @property string $workspace_gid
 * @property string|null $modified_at
 * @property string|null $resource_subtype
 * @property string|null $task_sync
 * @property string|null $task_sync_in
 * @property string|null $task_sync_out
 * @property int|null $num_hearts
 * @property int|null $num_likes
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid', 'name', 'created_at', 'workspace_gid'], 'required'],
            [['completed', 'num_hearts', 'num_likes'], 'integer'],
            [['completed_at', 'created_at', 'due_on', 'start_on', 'modified_at'], 'safe'],
            [['name', 'notes', 'work_done'], 'string'],
            [['task_sync_out', 'task_sync_in', 'task_sync', 'section_project_name', 'section_project_gid', 'gid', 'assignee_gid', 'assignee_name', 'assignee_status', 'permalink_url', 'project_gid', 'workspace_gid', 'resource_subtype'], 'string', 'max' => 255],
            [['parent_gid'], 'string', 'max' => 50],
            [['gid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'ID завдання (GID)',
            'section_project_gid' => 'ID проєкту секції',
            'section_project_name' => 'Назва проєкту секції',
            'name' => 'Назва завдання',
            'assignee_gid' => 'ID виконавця',
            'assignee_name' => 'Ім\'я виконавця',
            'assignee_status' => 'Статус виконавця',
            'completed' => 'Завершено',
            'completed_at' => 'Дата завершення',
            'created_at' => 'Дата створення',
            'due_on' => 'Кінцева дата завдання',
            'start_on' => 'Дата початку завдання',
            'notes' => 'Примітки',
            'work_done' => 'Виконана робота',
            'permalink_url' => 'Посилання на завдання',
            'project_gid' => 'ID проєкту',
            'workspace_gid' => 'ID робочого простору',
            'modified_at' => 'Дата останньої зміни',
            'resource_subtype' => 'Підтип завдання',
            'num_hearts' => 'Кількість сердечок',
            'num_likes' => 'Кількість лайків',
            'task_sync' => 'Синхронізація завдання',
            'task_sync_in' => 'Синхронізація завдання (вхід)',
            'task_sync_out' => 'Синхронізація завдання (вихід)',
        ];
    }


    private static function getToken()
    {
        return Yii::$app->params['tokenAsana'];
//        return Client::accessToken(Yii::$app->params['tokenAsana']);
    }

    public static function attribute($attribute)
    {
        $labels = (new Task)->attributeLabels();
        return $labels[$attribute] ?? $attribute;
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['gid' => 'project_gid']);
    }

    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['gid' => 'workspace_gid']);
    }

    public function getAssignee()
    {
        return $this->hasOne(AsanaUsers::class, ['gid' => 'assignee_gid']);
    }

    public function getSectionProject()
    {
        return $this->hasOne(Project::class, ['gid' => 'section_project_gid']);
    }

    public function getCustomFields()
    {
        return $this->hasMany(TaskCustomFields::class, ['task_gid' => 'gid']);
    }

    public function getStatus()
    {
        return $this->section_project_name;
    }

    public function getSubTasks()
    {
        return $this->hasMany(Task::class, ['parent_gid' => 'gid']);
    }

    /**
     * Get parent task
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStories(): \yii\db\ActiveQuery
    {
        return $this->hasMany(TaskStory::class, ['task_gid' => 'gid']);
    }

    public function getAttachments(): \yii\db\ActiveQuery
    {
        return $this->hasMany(TaskAttachment::class, ['task_gid' => 'gid']);
    }

    /**
     * Метод для получения файлов задачи через API Asana.
     *
     * @param string $task_gid Идентификатор задачи в Asana
     * @return mixed Ответ от API Asana
     */
    public static function getApiAttachments($task_gid)
    {
        $token = Yii::$app->params['tokenAsana'];
        $url = "https://app.asana.com/api/1.0/tasks/{$task_gid}/attachments";

        // Настройка HTTP-запроса с использованием cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            "Authorization: Bearer {$token}",
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            // Обработка ошибки
            Yii::error("Ошибка получения файлов задачи: HTTP {$httpCode}");
            return null;
        }
    }

    /**
     * Метод для получения отдельного файла (вложение) задачи через API Asana.
     *
     * @param string $attachment_gid Идентификатор вложения в Asana
     * @return mixed Ответ от API Asana
     */
    public static function getApiAttachment($attachment_gid)
    {
        $token = Yii::$app->params['tokenAsana'];
        $url = "https://app.asana.com/api/1.0/attachments/{$attachment_gid}";

        // Настройка HTTP-запроса с использованием cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            "Authorization: Bearer {$token}",
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            // Обработка ошибки
            Yii::error("Ошибка получения вложения: HTTP {$httpCode}");
            return null;
        }
    }

    public function getStatusList()
    {
        $sections = SectionProject::find()->where(['project_gid' => $this->project_gid])->all();
        return ArrayHelper::map($sections, 'gid', 'name');
    }

    public function getStatusColor()
    {
        if ($this->section_project_name == 'До роботи') {
            return 'success';
        } elseif ($this->section_project_name == 'В роботі') {
            return 'warning';
        } elseif ($this->section_project_name == 'Завершено') {
            return 'danger';
        } elseif ($this->section_project_name == 'Потребує уточнення') {
            return 'primary';
        } else {
            return 'info';
        }
    }

    /**
     * Get priority color
     *
     * @return string
     */
    public function getPriorityColor($priority): string
    {
        if ($priority == 'Високий') {
            return 'danger';
        } elseif ($priority == 'Призупинена') {
            return 'warning';
        } elseif ($priority == 'Низький') {
            return 'success';
        } else {
            return 'info';
        }
    }

    public function getTypeTask($type)
    {
        /**
         * Помилка
         * Покращення
         * Обслуговування
         * Нова функція
         */
        if ($type == 'Помилка') {
            return 'danger';
        } elseif ($type == 'Покращення') {
            return 'warning';
        } elseif ($type == 'Обслуговування') {
            return 'success';
        } elseif ($type == 'Нова функція') {
            return 'info';
        } else {
            return 'primary';
        }
    }

    public function getType()
    {
        foreach ($this->customFields as $customField) {
            if ($customField->name == 'Тип задачі') {
                $value = '<li class="sa-meta__item">
                                    <div title="Тип задачі" class="badge badge-sa-' . $this->getTypeTask($customField->display_value) . '">' . $customField->display_value . '</div>
                                </li>';
                return $value;
            }
        }
        return '';

    }

    public function getType2()
    {
        foreach ($this->customFields as $customField) {
            if ($customField->name == 'Тип задачі') {
                $value = [
                    'name' => $customField->display_value,
                    'color' => $this->getTypeTask($customField->display_value)
                ];
                return $value;
            }
        }
        return [
            'name' => '',
            'color' => ''
        ];

    }


    public function getTypeList($priority_gid = null)
    {
        $priorities = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1205860710071790']) //'Тип задачі'
            ->orderBy('display_value ASC')
            ->distinct()->all();
        $list = [];
        foreach ($priorities as $priority) {
            $list[$priority->enum_option_gid] = $priority->display_value;
        }
        return $priority_gid ? $list[$priority_gid] : $list;
    }

    /**
     * Список Час, план.
     */
    public function getTimePlan()
    {
        $time = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1202687202895300']) //'Час, план.'
            ->andWhere(['task_gid' => $this->gid])
            ->one();

        return $time && $time->display_value > 0 ? $time->display_value : 0;
    }

    /**
     * Список Час, факт.
     */
    public function getTimeFact()
    {
        $time = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1202687202895302']) //'Час, факт.'
            ->andWhere(['task_gid' => $this->gid])
            ->one();
        return $time && $time->display_value > 0 ? $time->display_value : 0;
    }

    /**
     * Оплата (замовник)
     */
    public function getPaymentCustomer()
    {
        $payment = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1208141652894034']) //'Оплата (замовник)'
            ->andWhere(['task_gid' => $this->gid])
            ->one();
        return $payment && $payment->display_value != null ? $payment->display_value : '—';
    }

    /**
     * Оплата (фахівець)
     */
    public function getPaymentSpecialist()
    {
        $payment = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1208291886801418']) //'Оплата (фахівець)'
            ->andWhere(['task_gid' => $this->gid])
            ->one();
        return $payment && $payment->display_value != null ? $payment->display_value : '—';
    }

    /**
     * Список Час, рахунок.
     */
    public function getTimeBill()
    {
        $time = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1205860798526183']) //'Час, рахунок.'
            ->andWhere(['task_gid' => $this->gid])
            ->one();
        return $time && $time->display_value > 0 ? $time->display_value : 0;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        foreach ($this->customFields as $customField) {
//            debugDie($customField);
            if ($customField->name == 'Приоритет') {
                $value = '<li class="sa-meta__item">
                                    <div title="Приоритет" class="badge badge-sa-' . $this->getPriorityColor($customField->display_value) . '">' . $customField->display_value . '</div>
                                </li>';
                return $value;
            }
        }
        return '';
    }

    public function getPriority2()
    {
        foreach ($this->customFields as $customField) {
//            debugDie($customField);
            if ($customField->name == 'Приоритет') {
                $value = [
                    'name' => $customField->display_value,
                    'color' => $this->getPriorityColor($customField->display_value)
                ];
                return $value;
            }
        }
        return [
            'name' => '',
            'color' => ''
        ];
    }

    public function getTaskPriority()
    {
        return $this->hasOne(TaskCustomFields::class, ['task_gid' => 'gid'])
            ->where(['custom_field_gid' => '1202674799521449']) //'Приоритет'
            ;
    }


    public function getTaskType()
    {
        return $this->hasOne(TaskCustomFields::class, ['task_gid' => 'gid'])
            ->where(['custom_field_gid' => '1205860710071790']) //'Тип задачі'
            ;
    }


    public function getNameGrid()
    {
        $name = '<div class="d-flex align-items-center">
                    <div>
                        <a href="' . Url::to(['update', 'gid' => $this->gid]) . '" class="text-reset">' . $this->name . '</a>
                        <div class="sa-meta mt-0">
                            <ul class="sa-meta__list">
                                <li class="sa-meta__item">
                                    <span title="Дата створення ' . $this->created_at . '" class="st-copy">' . Yii::$app->formatter->asDate($this->created_at, 'medium') . '</span>
                                </li>
                                <li class="sa-meta__item">
                                    <span title="Дата оновлення ' . $this->modified_at . '" class="st-copy">' . Yii::$app->formatter->asDate($this->modified_at, 'medium') . '</span>
                                </li>
                                <li class="sa-meta__item">
                                    <span title="Виконавець" class="st-copy">' . $this->assignee_name . '
                                   </span>
                                </li>
                                ' . $this->getPriority() . ' ' . $this->getType() . '
                            </ul>
                        </div>
                    </div>
                </div>';
        $name_other = '<div class="d-flex align-items-center">
                    <div>
<hr style="margin: 2px auto">
                        <div class="sa-meta mt-0">
                            <ul class="sa-meta__list">
                                <li class="sa-meta__item">
                                    <span title="Дата початку задачі" class="st-copy">Дата початку '
            . ($this->start_on ? Yii::$app->formatter->asDate($this->start_on, 'medium') : '-') .
            '</span>
                                </li>
                                <li class="sa-meta__item">
                                    <span title="Дата закінчення задачі" class="st-copy">Дата закінчення '
            . ($this->due_on ? Yii::$app->formatter->asDate($this->due_on, 'medium') : '-') .
            '</span>
                                </li>
                                <li class="sa-meta__item">
                                    <span title="Пріорітет" class="st-copy">Високий</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>';

        return $name;
    }

    public function getDueOn()
    {
        return $this->due_on ? Yii::$app->formatter->asDate($this->due_on, 'php:d.m.Y') : '';
    }

    public function getStartOn()
    {
        return $this->start_on ? Yii::$app->formatter->asDate($this->start_on, 'php:d.m.Y') : '';
    }

    public function getRealTime()
    {
        $time = TaskCustomFields::find()
            ->where(['task_gid' => $this->gid])
            ->andWhere(['name' => 'Час, факт.'])
            ->orderBy(['id' => SORT_DESC])->one();

        return $time ? $time->display_value : 0;

    }

    /**
     * Користувачі в проєкті
     */
    public function getAssigneeList($assignee_gid = null)
    {
        $assignees = Task::find()->select(['assignee_gid', 'assignee_name'])->where(['project_gid' => $this->project_gid])->distinct()->all();
        $list = [];
        foreach ($assignees as $assignee) {
            if ($assignee->assignee_name && $assignee->assignee_name != 'Private User') {
                $list[$assignee->assignee_gid] = $assignee->assignee_name;
            }
        }
        return $assignee_gid ? $list[$assignee_gid] : $list;
    }

    /**
     * Приоритети задач
     */
    public function getPriorityList($priority_gid = null)
    {
        $priorities = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1202674799521449']) //'Приоритет'
//            ->where(['name' => 'Приоритет'])
//            ->andWhere(['task_gid' => $this->gid])
            ->orderBy('display_value ASC')
            ->distinct()->all();
        $list = [];
        foreach ($priorities as $priority) {
            $list[$priority->enum_option_gid] = $priority->display_value;
        }
        return $priority_gid ? $list[$priority_gid] : $list;
    }


    /**
     * Сохранение кастомных полей для задачи с отслеживанием изменений
     */
    private static function saveCustomFields($customFields, $taskGid)
    {
        if (is_array($customFields)) {
            foreach ($customFields as $customField) {
                $customFieldModel = TaskCustomFields::findOne([
                    'task_gid' => $taskGid,
                    'custom_field_gid' => $customField->gid
                ]);

                $hasCustomFieldChanges = false;

                if ($customFieldModel) {
                    // Проверка значений существующего кастомного поля
                    if ($customFieldModel->display_value != $customField->display_value ||
                        $customFieldModel->enum_option_gid != ($customField->enum_value->gid ?? null) ||
                        $customFieldModel->enum_option_name != ($customField->enum_value->name ?? null) ||
                        $customFieldModel->number_value != ($customField->number_value ?? null)) {

                        $hasCustomFieldChanges = true;
                    }

                    // Обновляем значения кастомного поля
                    if ($hasCustomFieldChanges) {
                        $customFieldModel->display_value = $customField->display_value ?? null;
                        $customFieldModel->enum_option_gid = $customField->enum_value->gid ?? null;
                        $customFieldModel->enum_option_name = $customField->enum_value->name ?? null;
                        $customFieldModel->number_value = $customField->number_value ?? null;
                        $customFieldModel->save();
                    }

                } else {
                    // Если кастомного поля нет в базе, создаем новое
                    $newCustomField = new TaskCustomFields();
                    $newCustomField->task_gid = $taskGid;
                    $newCustomField->custom_field_gid = $customField->gid;
                    $newCustomField->name = $customField->name;
                    $newCustomField->type = $customField->type;
                    $newCustomField->display_value = $customField->display_value ?? null;
                    $newCustomField->enum_option_gid = $customField->enum_value->gid ?? null;
                    $newCustomField->enum_option_name = $customField->enum_value->name ?? null;
                    $newCustomField->number_value = $customField->number_value ?? null;
                    $newCustomField->save();

                }
            }
        }
    }


    /**
     * Создание задачи
     */
    public static function createTask($fullTask, $project_gid)
    {
        // Создание новой записи задачи
        $taskModel = new Task();
        $taskModel->gid = $fullTask->gid;
        $taskModel->name = !empty($fullTask->name) && isset($fullTask->name) ? $fullTask->name : '--- Без назви ---';
        $taskModel->assignee_gid = $fullTask->assignee->gid ?? null;
        $taskModel->section_project_gid = $fullTask->memberships[0]->section->gid ?? null;
        $taskModel->section_project_name = $fullTask->memberships[0]->section->name ?? null;
        $taskModel->assignee_name = $fullTask->assignee->name ?? null;
        $taskModel->assignee_status = $fullTask->assignee_status ?? null;
        $taskModel->completed = intval($fullTask->completed) ?? false;
        $taskModel->completed_at = isset($fullTask->completed_at) ? date('Y-m-d H:i:s', strtotime($fullTask->completed_at)) : null;
        $taskModel->created_at = date('Y-m-d H:i:s', strtotime($fullTask->created_at));
        $taskModel->due_on = $fullTask->due_on ?? null;
        $taskModel->start_on = $fullTask->start_on ?? null;
        $taskModel->notes = $fullTask->notes ?? null;
        $taskModel->permalink_url = $fullTask->permalink_url ?? null;
        $taskModel->project_gid = strval($project_gid);
        $taskModel->workspace_gid = $fullTask->workspace->gid ?? null;
        $taskModel->modified_at = isset($fullTask->modified_at) ? date('Y-m-d H:i:s', strtotime($fullTask->modified_at)) : null;
        $taskModel->resource_subtype = $fullTask->resource_subtype ?? null;
        $taskModel->num_hearts = $fullTask->num_hearts ?? 0;
        $taskModel->num_likes = $fullTask->num_likes ?? 0;

        if ($taskModel->save()) {
            // Сохранение кастомных полей задачи
            echo 'date: ' . $taskModel->created_at . ' date_mod: ' . $taskModel->modified_at .' Task created: ' . $taskModel->name . PHP_EOL;
            self::saveCustomFields($fullTask->custom_fields, $taskModel->gid);
            self::saveOrUpdateTaskStory($taskModel);
            self::saveOrUpdateAttachmentTask($taskModel);
            self::saveOrUpdateSubTask($taskModel);
        } else {
            echo "Error saving task with GID {$taskModel->gid}: " . print_r($taskModel->getErrors(), true) . PHP_EOL;
        }
    }


    /**
     * Обновление задачи
     */
    public static function updateTask($fullTask, $existingTask, $project_gid)
    {
        $taskModel = $existingTask;
        if ($taskModel) {
            // Обновление существующей записи задачи
            $taskModel->name = !empty($fullTask->name) && isset($fullTask->name) ? $fullTask->name : '--- Без назви ---';
            $taskModel->assignee_gid = $fullTask->assignee->gid ?? null;
            $taskModel->section_project_gid = $fullTask->memberships[0]->section->gid ?? null;
            $taskModel->section_project_name = $fullTask->memberships[0]->section->name ?? null;
            $taskModel->assignee_name = $fullTask->assignee->name ?? null;
            $taskModel->assignee_status = $fullTask->assignee_status ?? null;
            $taskModel->completed = intval($fullTask->completed) ?? false;
            $taskModel->completed_at = isset($fullTask->completed_at) ? date('Y-m-d H:i:s', strtotime($fullTask->completed_at)) : null;
            $taskModel->created_at = date('Y-m-d H:i:s', strtotime($fullTask->created_at));
            $taskModel->due_on = $fullTask->due_on ?? null;
            $taskModel->start_on = $fullTask->start_on ?? null;
            $taskModel->notes = $fullTask->notes ?? null;
            $taskModel->permalink_url = $fullTask->permalink_url ?? null;
            $taskModel->project_gid = strval($project_gid);
            $taskModel->workspace_gid = $fullTask->workspace->gid ?? null;
            $taskModel->modified_at = isset($fullTask->modified_at) ? date('Y-m-d H:i:s', strtotime($fullTask->modified_at)) : null;
            $taskModel->resource_subtype = $fullTask->resource_subtype ?? null;
            $taskModel->num_hearts = $fullTask->num_hearts ?? 0;
            $taskModel->num_likes = $fullTask->num_likes ?? 0;

            if ($taskModel->save()) {
                echo 'date: ' . $taskModel->created_at . ' date_mod: ' . $taskModel->modified_at . ' Task update: ' . $taskModel->name . PHP_EOL;
                // Сохранение
                self::saveCustomFields($fullTask->custom_fields, $taskModel->gid);
                self::saveOrUpdateTaskStory($taskModel);
                self::saveOrUpdateAttachmentTask($taskModel);
                self::saveOrUpdateSubTask($taskModel);
            } else {
                echo "Error saving task with GID {$taskModel->gid}: " . print_r($taskModel->getErrors(), true) . PHP_EOL;
            }
        }
    }

    /**
     * Получение списка изменений и комментариев для задачи
     */
    public static function saveOrUpdateTaskStory($task): bool
    {

        /* @var Task $task */
        $taskStory = TaskStory::getApiStories($task->gid);
        if (isset($taskStory['data']) && !empty($taskStory['data'])) {
            foreach ($taskStory['data'] as $story) {
                $model = TaskStory::find()->where(['gid' => $story['gid']])->one();
                if (!$model) {
                    $model = new TaskStory();
                }
                $model->gid = $story['gid'];
                $model->task_gid = $task->gid;
                $model->created_at = date('Y-m-d H:i:s', strtotime($story['created_at']));
                $model->created_by_gid = $story['created_by']['gid'];
                $model->created_by_name = $story['created_by']['name'];
                $model->created_by_resource_type = $story['type'];
                $model->text = $story['text'];
                $model->resource_subtype = $story['resource_subtype'];
                if (!$model->save()) {
                    Yii::error('Error saving TaskStory: ' . print_r($model->getErrors(), true));
                    return false;
                } else {
//                    echo 'TaskStory created' . $model->text . PHP_EOL;
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Получение списка файлов для задачи
     */
    public static function saveOrUpdateAttachmentTask($task)
    {

        $attachments = $task->getApiAttachments($task->gid);
        if (!empty($attachments['data'])) {
            foreach ($attachments['data'] as $attachment) {
                $att = $task->getApiAttachment($attachment['gid']);

                $model = TaskAttachment::find()->where(['gid' => $attachment['gid']])->one();
                if (!$model) {
                    $model = new TaskAttachment();
                }

                $att = $att['data'];
                $model->gid = $attachment['gid'];
                $model->task_gid = $task->gid;
                $model->created_at = date('Y-m-d H:i:s', strtotime($att['created_at']));
                $model->download_url = $att['download_url'];
                $model->name = $att['name'];
                $model->parent_gid = $att['parent']['gid'];
                $model->parent_name = $att['parent']['name'];
                $model->parent_resource_type = $att['parent']['resource_type'];
                $model->parent_resource_subtype = $att['parent']['resource_subtype'];
                $model->permanent_url = $att['permanent_url'];
                $model->resource_type = $att['resource_type'];
                $model->resource_subtype = $att['resource_subtype'];
                $model->view_url = $att['view_url'];


                if (!$model->save()) {
                    Yii::error('Error saving TaskAttachment: ' . print_r($model->getErrors(), true));
                    return false;
                } else {
//                    echo 'TaskAttachment created' . $model->name . PHP_EOL;
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Получение списка подзадач для задачи
     */
    public static function saveOrUpdateSubTask($task)
    {
        $subTask = self::getSubTask($task->gid);
        if (!empty($subTask['data'])) {
            $subTask = $subTask['data'];
            foreach ($subTask as $sub) {
//                print_r($sub); die;
                $sGid = $sub['gid'];
                $model = Task::find()->where(['gid' => $sGid])->one();
                if (!$model) {
                    $model = new Task();
                }
                $model->gid = $sGid;
                $model->parent_gid = $task->gid;
                $model->name = empty($sub['name']) ?? '--- Без назви ---';
                $model->assignee_gid = $sub['assignee']['gid'] ?? null;
                $model->assignee_name = $sub['assignee']['name'] ?? null;
                $model->completed = intval($sub['completed']) ?? 0;
                $model->completed_at = isset($sub['completed_at']) ? date('Y-m-d H:i:s', strtotime($sub['completed_at'])) : null;
                $model->notes = $sub['notes'] ?? null;
                $model->due_on = $sub['due_on'] ?? null;
                $model->created_at = date('Y-m-d H:i:s', strtotime($sub['created_at']));
                $model->modified_at = date('Y-m-d H:i:s', strtotime($sub['modified_at']));
                $model->workspace_gid = AsanaController::WORKSPACE_INGSOT_GID;
                if (!$model->save()) {
                    Yii::error('Error saving SubTask: ' . print_r($model->getErrors(), true));
                    return false;
                } else {
//                    echo 'SubTask created' . $model->name . PHP_EOL;
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Получение списка подзадач для задачи
     */
    private static function getSubTask($taskGid)
    {
        $accessToken = self::getToken(); // Получите токен доступа
        $url = "https://app.asana.com/api/1.0/tasks/{$taskGid}/subtasks";

        // Параметры для запроса
        $params = http_build_query([
            'opt_fields' => 'completed,completed_at,name,notes,assignee, assignee.name,due_on,created_at,modified_at'
        ]);

        // Инициализация cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
            // Добавление кастомных заголовков, если необходимо
            'Asana-Disable: new_user_task_lists',
            'Asana-Enable: new_goal_memberships'
        ]);

        // Выполнение запроса и получение результата
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        // Обработка ответа
        if ($httpCode == 200) {
            $subTasksAsana = json_decode($response, true);
            return $subTasksAsana;
        } else {
            echo "Error: Received HTTP code $httpCode\n";
            print_r($response);
            return null;
        }
    }


}
