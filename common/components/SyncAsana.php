<?php

namespace common\components;

use Asana\Client;
use common\models\Task;
use Yii;

class SyncAsana
{
    public static $TOKEN;

    public function __construct()
    {
        self::$TOKEN = Yii::$app->params['tokenAsana'];
    }

    const WORKSPACE_INGSOT_GID = '1202666709283080';
    const USER_IG = '1203674070841328'; // это я (Игорь Кривошей)

//    private static function getClient(): Client
//    {
//        return Client::accessToken(self::$TOKEN);
//    }


    /**
     * Создание задачи в Asana
     *
     * @param $data
     * @return mixed
     *
     *  $data = [
     *      'name' => 'Назва завдання'
     *      'notes' => '<p>Примітки</p>'
     *      'section_project_gid' => '1208837411259517'
     *      'assignee_gid' => '1203674070841328'
     *      'priority' => '1202674799522489'
     *      'type' => '1205860710071792'
     *  ]
     * @throws \Exception
     */
    public static function createTask()
    {

//        $asana = Client::accessToken(Yii::$app->params['tokenAsana']);
//
//        // Отключаем устаревшие API-изменения
//        $asana->options['headers']['Asana-Disable'] = 'new_goal_memberships,new_user_task_lists';
//
//        try {
//
//            // Создаем задачу
//            $task = $asana->tasks->createTask($data);
//            $task_gid = $task->gid; // Получаем GID созданной задачи
//
//            // Перемещаем задачу в нужную секцию
//            if (!empty($data['section_project_gid'])) {
//                $asana->sections->addTask($data['section_project_gid'], ['task' => $task_gid]);
//            }
//
//            return $task;
//
//        } catch (\Asana\Errors\InvalidRequestError $e) {
//            Yii::error("Asana API Error: " . $e->getMessage());
//            return ['error' => $e->getMessage(), 'response' => $e->response->raw_body];
//        }

        $tasks = Task::find()->where(['task_sync' => Task::CRON_STATUS_NEW])->all();
        print_r($tasks);
        foreach ($tasks as $task) {
            /** @var $task Task */
            $task_gid = $task->gid;
            $data = Task::prepareData($task_gid, Task::CRON_STATUS_NEW);
            try {
                self::requestCreate($task_gid, $data);
            } catch (\Asana\Errors\InvalidRequestError $e) {
                Yii::error("Asana API Error: " . $e->getMessage());
                Yii::error($e->response->raw_body);
            }
        }
    }

    public static function updateTask()
    {
        $tasks = Task::find()->where(['task_sync' => Task::CRON_STATUS_UPDATE])->all();
        foreach ($tasks as $task) {
            /** @var $task Task */
            $task_gid = $task->gid;
            $data = Task::prepareData($task_gid);
            try {
                self::requestUpdate($task_gid, $data);
            } catch (\Asana\Errors\InvalidRequestError $e) {
                Yii::error("Asana API Error: " . $e->getMessage());
                Yii::error($e->response->raw_body);
            }
        }
    }

    static function requestUpdate($taskGid, $data)
    {
        $accessToken = Yii::$app->params['tokenAsana'];
        $url = "https://app.asana.com/api/1.0/tasks/{$taskGid}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json"
        ]);

        // ✅ Оборачиваем параметры в "data"
        $payload = json_encode(['data' => $data]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        curl_close($ch);

        // Обработка ответа
        if ($response === false) {
            echo "Ошибка выполнения запроса: " . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['errors'])) {
                echo "Ошибка: " . print_r($responseData['errors'], true);
            } else {
                $task = Task::findOne(['gid' => $taskGid]);
                $task->task_sync = Task::CRON_STATUS_SUCCESS;
                $task->task_sync_out = date('Y-m-d H:i:s');
                $task->save();
                self::moveTaskToSection($taskGid, $task->section_project_gid);
                echo "Задача успешно обновлена." . PHP_EOL;
            }
        }
    }

    public static function moveTaskToSection($task_gid, $section_gid)
    {
        $accessToken = Yii::$app->params['tokenAsana'];
        $url = "https://app.asana.com/api/1.0/sections/{$section_gid}/addTask";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['data' => ['task' => $task_gid]]));

        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);
        if (isset($responseData['errors'])) {
            echo "Ошибка: " . print_r($responseData['errors'], true);
        } else {
            echo "Задача перемещена в новую секцию." . PHP_EOL;
        }
    }

    private static function requestCreate(string $task_gid, array $data)
    {
        $accessToken = Yii::$app->params['tokenAsana'];
        $url = "https://app.asana.com/api/1.0/tasks";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['data' => $data]));

        $response = curl_exec($ch);
        curl_close($ch);

        // Обработка ответа
        if ($response === false) {
            echo "Ошибка выполнения запроса: " . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['errors'])) {
                echo "Ошибка: " . print_r($responseData['errors'], true);
            } else {
                $task = Task::findOne(['gid' => $task_gid]);
                $task->task_sync = Task::CRON_STATUS_SUCCESS;
                $task->task_sync_out = date('Y-m-d H:i:s');
                $task->save();
                self::moveTaskToSection($task_gid, $task->section_project_gid);
                echo "Задача успешно создана." . PHP_EOL;
            }
        }
    }


}