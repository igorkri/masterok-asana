<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task_story".
 *
 * @property int $id
 * @property string|null $gid
 * @property string|null $task_gid
 * @property string|null $created_at
 * @property string|null $created_by_gid
 * @property string|null $created_by_name
 * @property string|null $created_by_resource_type
 * @property string|null $story_gid
 * @property string|null $resource_type
 * @property string|null $text
 * @property string|null $resource_subtype
 *
 * @property Task $taskG
 */
class TaskStory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_story';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['text'], 'string'],
            [['gid', 'task_gid', 'created_by_gid', 'created_by_name', 'created_by_resource_type', 'story_gid', 'resource_type', 'resource_subtype'], 'string', 'max' => 255],
            [['task_gid'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_gid' => 'gid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Идентификатор',
            'task_gid' => 'Идентификатор задачи',
            'created_at' => 'Дата создания',
            'created_by_gid' => 'Идентификатор создателя',
            'created_by_name' => 'Имя создателя',
            'created_by_resource_type' => 'Тип ресурса создателя',
            'story_gid' => 'Идентификатор истории',
            'resource_type' => 'Тип ресурса',
            'text' => 'Текст',
            'resource_subtype' => 'Подтип ресурса',
        ];
    }

    /**
     * Gets query for [[TaskG]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskG()
    {
        return $this->hasOne(Task::class, ['gid' => 'task_gid']);
    }


    /**
     * Метод для получения историй задачи через API Asana.
     *
     * @param string $task_gid Идентификатор задачи в Asana
     * @return mixed Ответ от API Asana
     */
    public static function getApiStories($task_gid)
    {
        $token = Yii::$app->params['tokenAsana'];
        $url = "https://app.asana.com/api/1.0/tasks/{$task_gid}/stories";

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
            Yii::error("Ошибка получения историй задачи: HTTP {$httpCode}");
            return null;
        }
    }


    /**
     * Метод для сохранения историй задачи в asana.
     *
     * @param string $task_gid Идентификатор задачи в Asana
     * @param array $data Данные для сохранения
     * @return bool|array Результат сохранения
     *
     */
    public static function saveStories($task_gid, $data)
    {
        if (empty($data) || !is_array($data)) {
            Yii::error("Некорректные данные для сохранения историй: " . print_r($data, true), __METHOD__);
            return ['success' => false, 'response' => null, 'error' => 'Некорректные данные'];
        }

        $token = Yii::$app->params['tokenAsana'];
        $url = "https://app.asana.com/api/1.0/tasks/{$task_gid}/stories";

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (!$jsonData) {
            Yii::error("Ошибка JSON-кодирования: " . json_last_error_msg(), __METHOD__);
            return ['success' => false, 'response' => null, 'error' => 'Ошибка кодирования JSON'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            "Authorization: Bearer {$token}",
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Проверка успешного ответа
        if ($httpCode === 201) {
            $decodedResponse = json_decode($response, true);
            return ['success' => true, 'response' => $decodedResponse];
        }

        // Обработка ошибки
        $decodedResponse = json_decode($response, true);
        $errorMessage = $decodedResponse['errors'][0]['message'] ?? "Неизвестная ошибка";

        Yii::error("Ошибка сохранения истории в Asana: HTTP {$httpCode} - {$errorMessage}", __METHOD__);

        return [
            'success' => false,
            'response' => $decodedResponse,
            'error' => $errorMessage
        ];
    }


}

