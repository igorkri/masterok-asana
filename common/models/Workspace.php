<?php

namespace common\models;

use Yii;
use yii\base\Model;

class Workspace extends Model
{

    const WORKSPACE_INGSOT_GID = '1202666709283080';




    /**
     * Получение новых задач, созданных после указанной даты
     */
    public static function getNewTasks($createdAfter)
    {
        $workspaceId = self::WORKSPACE_INGSOT_GID;
        $url = "https://app.asana.com/api/1.0/workspaces/{$workspaceId}/tasks/search?" .
            http_build_query([
                'opt_fields' => 'projects,created_at,modified_at,name',
                'created_on.after' => $createdAfter,
                'sort_by' => 'created_at',
                'sort_ascending' => 'false',
            ]);

        $tasks = self::fetchTasksFromAsana($url);

        return $tasks; // Возвращаем массив новых задач
    }

    /**
     * Получение задач, обновлённых после указанной даты
     */
    public static function getUpdatedTasks($modifiedAfter)
    {
        $workspaceId = self::WORKSPACE_INGSOT_GID;

        $url = "https://app.asana.com/api/1.0/workspaces/{$workspaceId}/tasks/search?" .
            http_build_query([
                'opt_fields' => 'projects,created_at,modified_at,name',
                'modified_on.after' => $modifiedAfter,
                'sort_by' => 'modified_at',
                'sort_ascending' => 'false',
            ]);

        $tasks = self::fetchTasksFromAsana($url);

        return $tasks; // Возвращаем массив обновлённых задач
    }

    /**
     * Выполнение запроса к API Asana и получение задач
     */
    private static function fetchTasksFromAsana($url)
    {
        $headers = [
            'accept: application/json',
            'authorization: Bearer ' . Yii::$app->params['tokenAsana'],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            echo "Ошибка запроса к Asana: HTTP $httpCode\n";
            echo "Ответ: $response\n";
            curl_close($ch);
            return [];
        }

        curl_close($ch);

        $data = json_decode($response, true);

        return $data['data'] ?? [];
    }



}