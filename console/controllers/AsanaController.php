<?php

namespace console\controllers;

use Asana;
use common\models\AsanaUsers;
use common\models\Project;
use common\models\Task;
use common\models\Workspace;
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
     * Получение новых задач за текущий день (раз на минуту)
     */
    public function actionNewTasks()
    {
        $client = $this->getToken();
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';
        $date = date('Y-m-d', time());
        echo "Дата фильтра: $date\n";
        $newTasks = Workspace::getNewTasks($date);
        foreach ($newTasks as $task) {
            /** @var Project $project */
            $project = Project::find()->where(['gid' => $task['projects'][0]['gid']])->one();
            $tGid = $task['gid'];
            $fullTask = $this->getTask($tGid);
            $existingTask = Task::findOne(['gid' => $tGid]);
            if ($existingTask) {
                Task::updateTask($fullTask, $existingTask, $project->gid);
            } else {
                Task::createTask($fullTask, $project->gid);
            }
        }
    }

    /**
     * Получение обновленных задач за текущий день (раз на минуту)
     */
    public function actionUpdatedTasks()
    {
        $client = $this->getToken();
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';
        $date = date('Y-m-d', time());
        echo "Дата фильтра: $date\n";
        $updatedTasks = Workspace::getUpdatedTasks($date);
        foreach ($updatedTasks as $task) {
            /** @var Project $project */
            $project = Project::find()->where(['gid' => $task['projects'][0]['gid']])->one();
            $tGid = $task['gid'];
            $fullTask = $this->getTask($tGid);
            $existingTask = Task::findOne(['gid' => $tGid]);
            if ($existingTask) {
                Task::updateTask($fullTask, $existingTask, $project->gid);
            } else {
                Task::createTask($fullTask, $project->gid);
            }
        }
    }


    /**
     * Обновление всех задач в проектах (раз в неделю)
     */
    public function actionUpdateAllTasks()
    {
        $client = $this->getToken();
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';

        try {
            $projects = Project::find()->all();

            foreach ($projects as $project) {
//                $tasksAsana = $client->tasks->getTasksForProject('1208836601411633');
                $tasksAsana = $client->tasks->getTasksForProject($project->gid);

                foreach ($tasksAsana as $task) {
                    $tGid = $task->gid;
                    $fullTask = $this->getTask($tGid);

                    // Проверка, существует ли задача в базе данных
                    $existingTask = Task::findOne(['gid' => $tGid]);

                    if ($existingTask) {
                        // Обновление существующей задачи и отслеживание изменений
                        Task::updateTask($fullTask, $existingTask, $project->gid);
                    } else {
                        Task::createTask($fullTask, $project->gid);
                    }
                }
            }
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }

    /**
     * Сохранение / обновление проектов (раз в сутки 00:00)
     */
    public function actionProjects(): void
    {
        $client = $this->getToken();
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';

        try {
            // Получаем проекты для конкретной рабочей области
            $projectsAsana = $client->projects->getProjectsForWorkspace(self::WORKSPACE_INGSOT_GID);
            foreach ($projectsAsana as $project) {
                if (Project::updateOrCreateProject($project)) { // Сохранение проекта
                    Project::saveSections($project->gid); // Сохранение секций проекта
                    Project::saveCustomFields($project->gid); // Сохранение кастомных полей проекта
                }
            }
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }


    /**
     * Сохранение / обновление пользователей (раз в сутки 00:00)
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
                $is_model = AsanaUsers::find()->where(['gid' => $uGid])->one();
                if ($is_model) {
                    $is_model->name = $user->name;
                    $is_model->resource_type = $user->resource_type;
                    if (!$is_model->save()) {
                        print_r($is_model->errors);
                        die;
                    }
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


//    /**
//     * Получение списка задач для всех проектов в рабочей области и их обновление/добавление в базу данных
//     */
//    public function actionProjectTasks()
//    {
//        $client = $this->getToken();
//        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';
//
//        try {
//            $projects = Project::find()->all();
//
//            foreach ($projects as $project) {
//                /** @var $project Project */
//                $tasksAsana = $client->tasks->getTasksForProject($project->gid);
//
//                foreach ($tasksAsana as $task) {
//                    $tGid = $task->gid;
//                    $fullTask = $this->getTask($tGid);
//
//                    // Проверка, существует ли задача в базе данных
//                    $existingTask = Task::findOne(['gid' => $tGid]);
//
//                    if ($existingTask) {
//                        // Обновление существующей задачи и отслеживание изменений
//                        Task::updateTask($fullTask, $project->gid);
//                    } else {
//                        Task::createTask($fullTask, $project->gid);
//                    }
//                }
//            }
//        } catch (\Asana\Errors\InvalidRequestError $e) {
//            echo "Error: " . $e->getMessage();
//            print_r($e->response->raw_body);
//        }
//    }



    /**
     * Получение информации о конкретной задаче по её GID
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
     * Создание тестовой задачи (заметка для себя - удалить после тестирования)
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

}
