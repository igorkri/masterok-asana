<?php

namespace console\controllers;

use Asana;
use common\models\AsanaProject;
use common\models\AsanaTask;
use common\models\AsanaUser;
use common\models\AsanaSubTask;
use common\models\AsanaUsers;
use Yii;
use yii\db\Exception;
use yii\console\Controller;

class AsanaController extends Controller
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
                $pGid = $project->gid;
                $is_model = AsanaProject::find()->where(['gid' => $pGid])->exists();
                if ($is_model) {
                    continue;
                }
                $new_model = new AsanaProject();
                $new_model->gid = $pGid;
                $new_model->name = $project->name;
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
                $uGid = $user->gid;
                $is_model = AsanaUsers::find()->where(['gid' => $uGid])->exists();
                if ($is_model) {
                    continue;
                }
                $new_model = new AsanaUsers();
                $new_model->gid = $uGid;
                $new_model->name = $user->name;
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
     * Получение списка задач для всех проектов в рабочей области
     */
    public function actionProjectTasks()
    {
        $client = $this->getToken();
// Добавляем заголовок для подавления предупреждения
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';  // или 'Asana-Disable'

        try {
            $projects = AsanaProject::find()->all();

            foreach ($projects as $project) {
                $tasksAsana = $client->tasks->getTasksForProject($project->gid);

                foreach ($tasksAsana as $task) {
                    $tGid = $task->gid;
                    $fullTask = $this->getTask($tGid);
//                    print_r($fullTask); die;
                    $is_model = AsanaTask::find()->where(['gid' => $tGid])->exists();
                    if ($is_model) {
                        continue;
                    }
                    $new_model = new AsanaTask();
                    $new_model->gid = $tGid;
                    $new_model->project_gid = $project->gid ?? null;
                    $new_model->assignee_gid = $fullTask->assignee->gid ?? null;
                    $new_model->status_gid = $fullTask->memberships[0]->section->gid ?? null;
                    $new_model->priority_gid = $fullTask->custom_fields[0]->enum_value->gid ?? null;
                    $new_model->type_gid = $fullTask->custom_fields[1]->enum_value->gid ?? null;
                    $new_model->name = $fullTask->name;
                    $new_model->notes = $fullTask->notes;
                    $new_model->completed = !empty($fullTask->completed) ? intval($fullTask->completed) : 0;
                    $new_model->permalink_url = $fullTask->permalink_url;
                    $new_model->workspace_gid = $fullTask->workspace->gid;
                    $new_model->followers = $fullTask->followers;
                    $new_model->created_at = date('Y-m-d H:i:s', strtotime($fullTask->created_at));
                    $new_model->modified_at = date('Y-m-d H:i:s', strtotime($fullTask->modified_at));

                    try {
                        if (!$new_model->save()) {
                            print_r($fullTask);
                            print_r($new_model->errors);
                            die;
                        } else {
                            $this->getSubTask($tGid);
                            echo "SAVED " . $new_model->name . PHP_EOL;
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
     * Получение информации о конкретной задаче по её ID
     */
    /**
     * Получение информации о конкретной задаче по её ID
     */
    private function getTask($taskGid)
    {
        $client = $this->getToken();

        // Добавляем оба заголовка отдельно
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
        $tasks = AsanaTask::find()->where(['completed' => 0])->all();

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
     * Получение списка подзадач для задачи
     */
    private function getSubTask($taskGid)
    {
        $client = $this->getToken();

        // Добавляем оба заголовка отдельно
        $client->options['headers']['Asana-Disable'] = 'new_user_task_lists';
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';

        try {
            $subTasksAsana = $client->tasks->getSubTasksForTask($taskGid, [
                'opt_fields' => 'completed,completed_at,name,notes,assignee,due_on,created_at,modified_at'
            ]);
            foreach ($subTasksAsana as $subTask) {
//                print_r($subTask); die();
                $sGid = $subTask->gid;
                $is_model = AsanaSubTask::find()->where(['gid' => $sGid])->exists();
                if ($is_model) {
                    continue;
                }
                $new_model = new AsanaSubTask();
                $new_model->gid = $sGid;
                $new_model->task_gid = $taskGid;
                $new_model->name = $subTask->name;
                $new_model->note = $subTask->notes;
                $new_model->complete = intval($subTask->completed);
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
                'assignee' => self::USER_IV, // замените на нужный User GID
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
