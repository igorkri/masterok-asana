<?php

namespace console\controllers;

use Asana;
use common\models\AsanaUsers;
use common\models\Project;
use common\models\ProjectCustomFieldEnumOptions;
use common\models\ProjectCustomFields;
use common\models\SectionProject;
use common\models\Task;
use common\models\TaskAttachment;
use common\models\TaskChanges;
use common\models\TaskCustomFields;
use common\models\TaskStory;
use Yii;
use yii\db\Exception;
use yii\console\Controller;

class _AsanaController extends Controller
{

    public static $TOKEN;

    public function init()
    {
        parent::init();
//        var_dump(Yii::$app->params); // Временная отладка
        self::$TOKEN = Yii::$app->params['tokenAsana'];
    }

    const WORKSPACE_INGSOT_GID = '1202666709283080';
    const USER_IV = '1204086170346983';
    const USER_IG = '1203674070841328';

    private function getToken()
    {
        return Asana\Client::accessToken(self::$TOKEN);
    }

    /**
     * Сохранение списка проектов
     */
    public function actionProjects()
    {
        $client = $this->getToken();
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';

        try {
            // Получаем проекты для конкретной рабочей области
            $projectsAsana = $client->projects->getProjectsForWorkspace(self::WORKSPACE_INGSOT_GID);
            foreach ($projectsAsana as $project) {

                print_r($project) . PHP_EOL;

                $pGid = $project->gid;
                $is_model = Project::find()->where(['gid' => $pGid])->exists();
                if ($is_model) {
                    continue;
                }
                $new_model = new Project();
                $new_model->gid = intval($pGid);
                $new_model->name = $project->name;
                $new_model->workspace_gid = intval(self::WORKSPACE_INGSOT_GID);
                $new_model->resource_type = $project->resource_type;
                try {
                    if (!$new_model->save()) {
                        print_r($new_model->errors);
                        die;
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }

    /**
     * Получение section для проекта
     *
     *
     * stdClass Object
     * (
     * [gid] => 1202674272931464
     * [name] => До роботи
     * [resource_type] => section
     * )
     * stdClass Object
     * (
     * [gid] => 1202674272931470
     * [name] => Потребує уточнення
     * [resource_type] => section
     * )
     * stdClass Object
     * (
     * [gid] => 1202674272931468
     * [name] => Виконується
     * [resource_type] => section
     * )
 */
    public function actionSections()
    {
        $client = $this->getToken();
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';

        try {
            $projects = Project::find()->all();
            foreach ($projects as $project) {
                $sections = $client->sections->getSectionsForProject($project->gid);
                foreach ($sections as $section) {
                    //print_r($section) . PHP_EOL; die;
                    $sGid = intval($section->gid);
                    $is_model = SectionProject::find()->where(['gid' => $sGid])->exists();
                    if ($is_model) {
                        continue;
                    }
                    $new_model = new SectionProject();
                    $new_model->gid = $sGid;
                    $new_model->name = $section->name;
                    $new_model->project_gid = intval($project->gid);
                    $new_model->resource_type = $section->resource_type;
                    try {
                        if (!$new_model->save()) {
                            print_r($new_model->errors);
                            die;
                        }
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                }
            }
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }


    /**
     * Получение custom_fields для проекта через cURL и сохранение в базу данных
     */
    public function actionCustomFields()
    {
        // Ваш токен доступа
        $accessToken = self::$TOKEN;

        try {
            // Получение всех проектов из базы данных
            $projects = Project::find()->all();

            foreach ($projects as $project) {
                // Инициализация cURL
                $ch = curl_init();

                // URL для запроса кастомных полей
                $url = "https://app.asana.com/api/1.0/projects/{$project->gid}/custom_field_settings";

                // Установка параметров cURL
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: application/json',
                    "Authorization: Bearer $accessToken",
                ]);

                // Выполнение запроса
                $response = curl_exec($ch);

                // Проверка на ошибки cURL
                if (curl_errno($ch)) {
                    throw new \Exception('cURL error: ' . curl_error($ch));
                }

                // Закрытие cURL
                curl_close($ch);

                // Декодирование и обработка ответа
                $data = json_decode($response, true);

                if (isset($data['data']) && !empty($data['data'])) {
                    foreach ($data['data'] as $customFieldData) {
                        // Проверка существования кастомного поля в базе данных
                        $customField = ProjectCustomFields::findOne(
                            ['gid' => $customFieldData['custom_field']['gid'], 'project_gid' => $project->gid]
                        );

                        if (!$customField) {
                            // Создание нового кастомного поля, если не существует
                            $customField = new ProjectCustomFields();
                            $customField->gid = $customFieldData['custom_field']['gid'];
                            $customField->project_gid = strval($project->gid);
                            $customField->name = $customFieldData['custom_field']['name'];
                            $customField->type = $customFieldData['custom_field']['type'];
                            $customField->resource_type = $customFieldData['custom_field']['resource_type'];
                            $customField->resource_subtype = $customFieldData['custom_field']['resource_subtype'];
                            $customField->is_important = intval($customFieldData['is_important']) ?? false;

                            if (!$customField->save()) {
                                // Вывод ошибок сохранения
                                echo "Error saving custom field with GID {$customField->gid}: " . print_r($customField->getErrors(), true) . PHP_EOL;
                            }
                        }

                        // Проверка и добавление значений перечисления (enum_options), если они существуют
                        if (isset($customFieldData['custom_field']['enum_options']) && is_array($customFieldData['custom_field']['enum_options'])) {
                            foreach ($customFieldData['custom_field']['enum_options'] as $enumOptionData) {
                                // Проверка существования enum_option в базе данных
                                $enumOption = ProjectCustomFieldEnumOptions::findOne(['gid' => $enumOptionData['gid']]);

                                if (!$enumOption) {
                                    // Создание нового enum_option, если не существует
                                    $enumOption = new ProjectCustomFieldEnumOptions();
                                    $enumOption->gid = $enumOptionData['gid'];
                                    $enumOption->custom_field_gid = $customField->gid;
                                    $enumOption->name = $enumOptionData['name'];
                                    $enumOption->color = $enumOptionData['color'];
                                    $enumOption->enabled = intval($enumOptionData['enabled']);
                                    $enumOption->resource_type = $enumOptionData['resource_type'];

                                    if (!$enumOption->save()) {
                                        // Вывод ошибок сохранения
                                        echo "Error saving enum option with GID {$enumOption->gid}: " . print_r($enumOption->getErrors(), true) . PHP_EOL;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    echo "No custom fields found for project with GID: " . $project->gid . PHP_EOL;
                }
            }
        } catch (\Exception $e) {
            // Общая обработка ошибок
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }


    /**
     * Получение списка пользователей
     */
    public function actionUsers()
    {
        $client = $this->getToken();

        // Добавляем заголовок для подавления предупреждения
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';  // или 'Asana-Disable'

        try {
            $users = $client->users->getUsersForWorkspace(self::WORKSPACE_INGSOT_GID);
            foreach ($users as $user) {
//                print_r($user) . PHP_EOL; die;
                $uGid = $user->gid;
                $is_model = AsanaUsers::find()->where(['gid' => $uGid])->exists();
                if ($is_model) {
                    continue;
                }
                $new_model = new AsanaUsers();
                $new_model->gid = $uGid;
                $new_model->name = $user->name;
                $new_model->resource_type = $user->resource_type;
                try {
                    if (!$new_model->save()) {
                        print_r($new_model->errors);
                        die;
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }


    /**
     * Получение списка задач для всех проектов в рабочей области и их обновление/добавление в базу данных
     */
    public function actionProjectTasks()
    {
        $client = $this->getToken();
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';

        try {
            $projects = Project::find()->all();

            foreach ($projects as $project) {
                $tasksAsana = $client->tasks->getTasksForProject($project->gid);

                foreach ($tasksAsana as $task) {
                    $tGid = $task->gid;
//                    $fullTask = $this->getTask('1208475558640591');
//                    print_r($fullTask); die();
                    $fullTask = $this->getTask($tGid);

                    // Проверка, существует ли задача в базе данных
                    $existingTask = Task::findOne(['gid' => $tGid]);

                    if ($existingTask) {
                        // Обновление существующей задачи и отслеживание изменений
                        $this->updateTask($existingTask, $fullTask);
                    } else {
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
                        $taskModel->project_gid = strval($project->gid);
                        $taskModel->workspace_gid = $fullTask->workspace->gid ?? null;
                        $taskModel->modified_at = isset($fullTask->modified_at) ? date('Y-m-d H:i:s', strtotime($fullTask->modified_at)) : null;
                        $taskModel->resource_subtype = $fullTask->resource_subtype ?? null;
                        $taskModel->num_hearts = $fullTask->num_hearts ?? 0;
                        $taskModel->num_likes = $fullTask->num_likes ?? 0;

                        if ($taskModel->save()) {
                            // Сохранение кастомных полей задачи
                            $this->saveCustomFields($fullTask->custom_fields, $taskModel->gid);
                        } else {
                            echo "Error saving task with GID {$taskModel->gid}: " . print_r($taskModel->getErrors(), true) . PHP_EOL;
                        }
                    }
                }
            }
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }

    /**
     * Сравнение и обновление существующей задачи с сохранением изменений
     */
    private function updateTask($existingTask, $fullTask)
    {
        $fieldsToCompare = [
            'name', 'assignee_gid', 'assignee_name', 'assignee_status', 'completed',
            'completed_at', 'created_at', 'due_on', 'start_on', 'notes',
            'permalink_url', 'project_gid', 'workspace_gid', 'modified_at',
            'resource_subtype', 'num_hearts', 'num_likes', 'section_project_gid'
        ];

        $hasChanges = false;
        $i = 1;
        // Сравнение основных полей задачи
        foreach ($fieldsToCompare as $field) {

            if ($field === 'project_gid' || $field === 'workspace_gid'){
                continue;
            }

            $oldValue = $existingTask->$field;
            if ($field === 'completed_at' || $field === 'created_at' || $field === 'modified_at') {
                $newValue = $fullTask->$field ? date('Y-m-d H:i:s', strtotime($fullTask->$field)) : null;
            } else {
                $newValue = $fullTask->$field ?? null;
            }
            if(hash('sha256', $oldValue) === hash('sha256', $newValue) || $field === 'assignee_gid' || $field === 'assignee_name'){
                continue;
            }
            echo $i .  ' :' . $field . PHP_EOL;
            echo Task::attribute($field) . ' (старое значение): ' . trim($oldValue) . PHP_EOL;
            echo Task::attribute($field) . ' (новое значение) : ' . trim($newValue) . PHP_EOL;
//            print_r($fullTask);
//            echo hash('sha256', $oldValue) . ' - ' . hash('sha256', $newValue) . PHP_EOL;
            echo '-------------------' . PHP_EOL;
            // Проверка на изменение значения поля
            if ($oldValue != $newValue) {
                $hasChanges = true;

                // Создание записи об изменении в таблице task_changes
                $change = new TaskChanges();
                $change->task_gid = $existingTask->gid;
                $change->field = $field;
                $change->old_value = is_string($oldValue) ? $oldValue : json_encode($oldValue);
                $change->new_value = is_string($newValue) ? $newValue : json_encode($newValue);
                $change->changed_at = date('Y-m-d H:i:s');
                $change->save();

                // Обновление значения поля в задаче
                $existingTask->$field = $newValue;
            }
        }

        // Сравнение кастомных полей задачи
        if (isset($fullTask->custom_fields) && is_array($fullTask->custom_fields)) {
            foreach ($fullTask->custom_fields as $customField) {
                $customFieldModel = TaskCustomFields::findOne([
                    'task_gid' => $existingTask->gid,
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

                        // Логируем изменения для кастомного поля
                        $change = new TaskChanges();
                        $change->task_gid = $existingTask->gid;
                        $change->field = 'custom_field_' . $customField->gid;
                        $change->old_value = json_encode([
                            'display_value' => $customFieldModel->display_value,
                            'enum_option_gid' => $customFieldModel->enum_option_gid,
                            'enum_option_name' => $customFieldModel->enum_option_name,
                            'number_value' => $customFieldModel->number_value,
                        ]);
                        $change->new_value = json_encode([
                            'display_value' => $customField->display_value ?? null,
                            'enum_option_gid' => $customField->enum_value->gid ?? null,
                            'enum_option_name' => $customField->enum_value->name ?? null,
                            'number_value' => $customField->number_value ?? null,
                        ]);
                        $change->changed_at = date('Y-m-d H:i:s');
                        $change->save();
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
                    $newCustomField->task_gid = $existingTask->gid;
                    $newCustomField->custom_field_gid = $customField->gid;
                    $newCustomField->name = $customField->name;
                    $newCustomField->type = $customField->type;
                    $newCustomField->display_value = $customField->display_value ?? null;
                    $newCustomField->enum_option_gid = $customField->enum_value->gid ?? null;
                    $newCustomField->enum_option_name = $customField->enum_value->name ?? null;
                    $newCustomField->number_value = $customField->number_value ?? null;
                    $newCustomField->save();

                    // Логируем добавление нового кастомного поля
                    $change = new TaskChanges();
                    $change->task_gid = $existingTask->gid;
                    $change->field = 'custom_field_' . $customField->gid;
                    $change->old_value = null;
                    $change->new_value = json_encode([
                        'display_value' => $customField->display_value ?? null,
                        'enum_option_gid' => $customField->enum_value->gid ?? null,
                        'enum_option_name' => $customField->enum_value->name ?? null,
                        'number_value' => $customField->number_value ?? null,
                    ]);
                    $change->changed_at = date('Y-m-d H:i:s');
                    $change->save();
                }
            }
        }

        // Если были изменения, сохраняем задачу
        if ($hasChanges) {
            $existingTask->save();
        }
    }

    /**
     * Сохранение кастомных полей для задачи с отслеживанием изменений
     */
    private function saveCustomFields($customFields, $taskGid)
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

                        // Логируем изменения для кастомного поля
//                        $change = new TaskChanges();
//                        $change->task_gid = $taskGid;
//                        $change->field = 'custom_field_' . $customField->gid;
//                        $change->old_value = json_encode([
//                            'display_value' => $customFieldModel->display_value,
//                            'enum_option_gid' => $customFieldModel->enum_option_gid,
//                            'enum_option_name' => $customFieldModel->enum_option_name,
//                            'number_value' => $customFieldModel->number_value,
//                        ]);
//                        $change->new_value = json_encode([
//                            'display_value' => $customField->display_value ?? null,
//                            'enum_option_gid' => $customField->enum_value->gid ?? null,
//                            'enum_option_name' => $customField->enum_value->name ?? null,
//                            'number_value' => $customField->number_value ?? null,
//                        ]);
//                        $change->changed_at = date('Y-m-d H:i:s');
//                        $change->save();
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

                    // Логируем добавление нового кастомного поля
//                    $change = new TaskChanges();
//                    $change->task_gid = $taskGid;
//                    $change->field = 'custom_field_' . $customField->gid;
//                    $change->old_value = null;
//                    $change->new_value = json_encode([
//                        'display_value' => $customField->display_value ?? null,
//                        'enum_option_gid' => $customField->enum_value->gid ?? null,
//                        'enum_option_name' => $customField->enum_value->name ?? null,
//                        'number_value' => $customField->number_value ?? null,
//                    ]);
//                    $change->changed_at = date('Y-m-d H:i:s');
//                    $change->save();
                }
            }
        }
    }



    /**
     * Получение информации о конкретной задаче по её ID
     */
    private function getTask($taskGid)
    {
        $client = $this->getToken();

        // Добавляем заголовки
        $client->options['headers']['Asana-Disable'] = 'new_user_task_lists';
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';

        try {
            return $client->tasks->getTask($taskGid);
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }


    /**
     * Обновление статуса выполнения задач
     */
    public function actionUpdateCompleted()
    {
        $tasks = Task::find()->where(['completed' => 0])->all();

        $client = $this->getToken();

        foreach ($tasks as $task) {
            $taskAsana = $client->tasks->getTask($task->gid);
            $task->completed = $taskAsana->completed;
            try {
                if (!$task->save()) {
                    print_r($task->errors);
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    /**
     * Получение списка изменений и комментариев для задачи
     */
    public function actionTaskStory()
    {
        $tasks = Task::find()->all();
        foreach ($tasks as $task) {
            /* @var Task $task */
            $taskStory = TaskStory::getApiStories($task->gid);
            if(isset($taskStory['data']) && !empty($taskStory['data'])){
                foreach ($taskStory['data'] as $story){
                    $model = TaskStory::find()->where(['gid' => $story['gid']])->one();
                    if(!$model){
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
                    if(!$model->save()){
                        print_r($model->errors);
                    } else {
                        echo 'TaskStory created' . $model->text . PHP_EOL;
                    }
                }
            }

        }
    }

    /**
     * Получение списка файлов для задачи
     */
    public function actionAttachmentTask()
    {
        $tasks = Task::find()->all();
        foreach ($tasks as $task) {
            $attachments = $task->getApiAttachments($task->gid);
            if (!empty($attachments['data'])) {
                foreach ($attachments['data'] as $attachment) {
                    $att = $task->getApiAttachment($attachment['gid']);

                    $model = TaskAttachment::find()->where(['gid' => $attachment['gid']])->one();
                    if (!$model) {
                        $model = new TaskAttachment();
                    }
                    /**
                     * 'id' => 'ID',
                     * 'task_gid' => 'Ідентифікатор завдання',
                     * 'attachment_gid' => 'Ідентифікатор вкладення',
                     * 'created_at' => 'Дата створення',
                     * 'download_url' => 'URL для завантаження',
                     * 'name' => 'Назва',
                     * 'parent_gid' => 'Ідентифікатор батьківського елемента',
                     * 'parent_name' => 'Назва батьківського елемента',
                     * 'parent_resource_type' => 'Тип батьківського ресурсу',
                     * 'parent_resource_subtype' => 'Підтип батьківського ресурсу',
                     * 'permanent_url' => 'Постійний URL',
                     * 'resource_type' => 'Тип ресурсу',
                     * 'resource_subtype' => 'Підтип ресурсу',
                     * 'view_url' => 'URL для перегляду',
                     */
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
                        print_r($model->errors);
                    } else {
                        echo 'TaskAttachment created' . $model->name . PHP_EOL;
                    }
                }
            }
        }
    }

    /**
     * Получение списка подзадач для задачи
     */
    public function actionSubTask()
    {
        $tasks = Task::find()->all();
        foreach ($tasks as $task) {
            $subTask = $this->getSubTask($task->gid);
            if(!empty($subTask['data'])){
                $subTask = $subTask['data'];
                foreach ($subTask as $sub){
                    $sGid = $sub['gid'];
                    $is_model = Task::find()->where(['gid' => $sGid])->exists();
                    if ($is_model) {
                        continue;
                    }
                    $new_model = new Task();
                    $new_model->gid = $sGid;
                    $new_model->parent_gid = $task->gid;
                    $new_model->name = $sub['name'] ?? '--- Без назви ---';
                    $new_model->assignee_gid = $sub['assignee']['gid'] ?? null;
                    $new_model->assignee_name = $sub['assignee']['name'] ?? null;
                    $new_model->completed = intval($sub['completed']) ?? 0;
                    $new_model->completed_at = isset($sub['completed_at']) ? date('Y-m-d H:i:s', strtotime($sub['completed_at'])) : null;
                    $new_model->notes = $sub['notes'] ?? null;
                    $new_model->due_on = $sub['due_on'] ?? null;
                    $new_model->created_at = date('Y-m-d H:i:s', strtotime($sub['created_at']));
                    $new_model->modified_at = date('Y-m-d H:i:s', strtotime($sub['modified_at']));
                    $new_model->workspace_gid = self::WORKSPACE_INGSOT_GID;
                    if(!$new_model->save()){
                        print_r($new_model->errors);
//                        die;
                    } else {
                        //$new_model->link('subTask', $task);
                        echo 'SubTask created' . $new_model->name . PHP_EOL;
                    }
                }
//                print_r($subTask); die;
            }
        }

    }



    /**
     * Получение списка подзадач для задачи
     */
    private function getSubTask($taskGid)
    {
        $accessToken = self::$TOKEN; // Получите токен доступа
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


    /**
     * Создание тестовой задачи
     */
    public function actionCreateTestTask()
    {
        $asana = $this->getToken();

        echo "\n TEST CREATE ASANA \n";

        try {
            $create = $asana->tasks->createTask([
                'workspace' => self::WORKSPACE_INGSOT_GID,
                'projects' => '1203001648489726', // замените на ваш Project ID
                'name' => 'Test Hello World! brama',
                'assignee' => self::USER_IG, // замените на нужный User GID
                'notes' => 'This is a task for testing the Asana API :)',
            ]);

            print_r($create);
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }

    public function actionCheckToken()
    {
        $client = Asana\Client::accessToken(self::$TOKEN);
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';

        try {
            // Попытка получить список проектов в рабочей области
            $workspaceGid = 'your_workspace_gid'; // Замените на ваш GID рабочей области
            $projects = $client->projects->getProjectsForWorkspace($workspaceGid);

            echo "Token is valid. List of projects:" . PHP_EOL;
            foreach ($projects as $project) {
                echo 'Project: ' . $project->name . ' (GID: ' . $project->gid . ')' . PHP_EOL;
            }
        } catch (\Asana\Errors\ForbiddenError $e) {
            echo "Forbidden Error: Token does not have sufficient permissions." . PHP_EOL;
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Invalid Request Error: Check if the GID is correct and token has access." . PHP_EOL;
        } catch (\Asana\Errors\AsanaError $e) {
            echo "Asana Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function actionTestWebhookCreation()
    {
        $client = Asana\Client::accessToken(self::$TOKEN); // Замените на ваш токен
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';


        $workspaceGid = self::WORKSPACE_INGSOT_GID; // Замените на ваш GID рабочей области
        $targetUrl = 'https://asana.masterok-market.com.ua/webhook-handler'; // Замените на ваш URL

        try {
            $webhook = $client->webhooks->create([
                'resource' => $workspaceGid, // Укажите GID рабочей области или проекта
                'target' => $targetUrl, // URL, на который будут отправляться данные вебхука
            ]);

            echo "Webhook created successfully!" . PHP_EOL;
            print_r($webhook);
        } catch (\Asana\Errors\ForbiddenError $e) {
            echo "Forbidden Error: Token does not have permission to create a webhook." . PHP_EOL;
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Invalid Request Error: " . $e->getMessage() . PHP_EOL;
            print_r($e->response->raw_body);
        } catch (\Asana\Errors\AsanaError $e) {
            echo "Asana Error: " . $e->getMessage() . PHP_EOL;
        }
    }

}
