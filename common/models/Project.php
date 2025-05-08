<?php

namespace common\models;

use Asana\Client;
use console\controllers\AsanaController;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property int $gid
 * @property int $workspace_gid
 * @property string $resource_type
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    private static function getToken()
    {
//        return Yii::$app->params['tokenAsana'];
        return Client::accessToken(Yii::$app->params['tokenAsana']);
    }

    private static function createProject($project): bool
    {
        $new_model = new Project();
        $new_model->gid = strval($project->gid);
        $new_model->name = $project->name;
        $new_model->workspace_gid = strval(AsanaController::WORKSPACE_INGSOT_GID);
        $new_model->resource_type = $project->resource_type;
        try {
            if (!$new_model->save()) {
                Yii::error($new_model->errors, __METHOD__);
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return true;
    }

    private static function updateProject($project): bool
    {
        $is_model = Project::find()->where(['gid' => $project->gid])->one();
        $is_model->name = $project->name;
        $is_model->resource_type = $project->resource_type;
        try {
            if (!$is_model->save()) {
                Yii::error($is_model->errors, __METHOD__);
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'gid', 'workspace_gid', 'resource_type'], 'required'],
            [['gid', 'workspace_gid'], 'integer'],
            [['name', 'resource_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва проєкту',
            'gid' => 'Gid',
            'workspace_gid' => 'Workspace Gid',
            'resource_type' => 'Resource Type',
        ];
    }

    public function getTaskQty()
    {
        return $this->hasMany(Task::class, ['project_gid' => 'gid'])->count();
    }

    public function getTasks()
    {
        return $this->hasMany(Task::class, ['gid' => 'project_gid']);
    }

    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['gid' => 'workspace_gid']);
    }


    /**
     * Задачі які потребують виконання
     */
    public function getTaskExecution()
    {
        if (Yii::$app->user->isGuest) {
            return null; // Возвращаем null, если пользователь не авторизован
        }

        $user = Yii::$app->user->identity;
        if ($user === null || !isset($user->user_asana_gid)) {
            return null; // Возвращаем null, если данные пользователя отсутствуют
        }

        return $this->hasMany(Task::class, ['project_gid' => 'gid'])
            ->where(['section_project_name' => 'До роботи'])
            ->andWhere(['assignee_gid' => $user->user_asana_gid])
            ->count();
    }


    /**
     * Назва проєкту
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Обновление \ создание проекта
     */
    public static function updateOrCreateProject($project)
    {
        $pGid = $project->gid;
        $is_model = Project::find()->where(['gid' => $pGid])->one();
        if ($is_model) {
            return self::updateProject($project);
        } else {
            return self::createProject($project);
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
    public static function saveSections($project_gid)
    {
        $client = self::getToken();
        $client->options['headers']['Asana-Enable'] = 'new_goal_memberships';
        try {
            $sections = $client->sections->getSectionsForProject($project_gid);
            foreach ($sections as $section) {
                $sGid = intval($section->gid);
                $is_model = SectionProject::find()->where(['gid' => $sGid])->exists();
                if ($is_model) {
                    continue;
                }
                $new_model = new SectionProject();
                $new_model->gid = $sGid;
                $new_model->name = $section->name;
                $new_model->project_gid = intval($project_gid);
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
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }


    /**
     * Получение custom_fields для проекта через cURL и сохранение в базу данных
     */
    public static function saveCustomFields($project_gid)
    {
        // Ваш токен доступа
        $accessToken = self::getToken();

        try {
            // Инициализация cURL
            $ch = curl_init();

            // URL для запроса кастомных полей
            $url = "https://app.asana.com/api/1.0/projects/{$project_gid}/custom_field_settings";

            // Установка параметров cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                "Authorization: Bearer " . Yii::$app->params['tokenAsana'],
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
                        ['gid' => $customFieldData['custom_field']['gid'], 'project_gid' => $project_gid]
                    );

                    if (!$customField) {
                        // Создание нового кастомного поля, если не существует
                        $customField = new ProjectCustomFields();
                        $customField->gid = $customFieldData['custom_field']['gid'];
                        $customField->project_gid = strval($project_gid);
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
                echo "No custom fields found for project with GID: " . $project_gid . PHP_EOL;
            }

        } catch (\Exception $e) {
            // Общая обработка ошибок
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

}

