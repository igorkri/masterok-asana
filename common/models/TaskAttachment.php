<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task_attachment".
 *
 * @property int $id
 * @property string|null $task_gid
 * @property string|null $gid
 * @property string|null $created_at
 * @property string|null $download_url
 * @property string|null $name
 * @property string|null $parent_gid
 * @property string|null $parent_name
 * @property string|null $parent_resource_type
 * @property string|null $parent_resource_subtype
 * @property string|null $permanent_url
 * @property string|null $resource_type
 * @property string|null $resource_subtype
 * @property string|null $view_url
 *
 * @property Task $taskG
 */
class TaskAttachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['task_gid', 'gid', 'name', 'parent_gid', 'parent_name', 'parent_resource_type', 'parent_resource_subtype', 'resource_type', 'resource_subtype'], 'string', 'max' => 255],
            [['download_url', 'permanent_url', 'view_url'], 'string'],
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
            'task_gid' => 'Ідентифікатор завдання',
            'gid' => 'Ідентифікатор вкладення',
            'created_at' => 'Дата створення',
            'download_url' => 'URL для завантаження',
            'name' => 'Назва',
            'parent_gid' => 'Ідентифікатор батьківського елемента',
            'parent_name' => 'Назва батьківського елемента',
            'parent_resource_type' => 'Тип батьківського ресурсу',
            'parent_resource_subtype' => 'Підтип батьківського ресурсу',
            'permanent_url' => 'Постійний URL',
            'resource_type' => 'Тип ресурсу',
            'resource_subtype' => 'Підтип ресурсу',
            'view_url' => 'URL для перегляду',
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

    /*
     * Возвращает имя файла
     */
    public function getName()
    {
        return $this->name;
    }

    /*
     * Возвращает расширение файла по его имени
     */
    public function getExtension()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    /*
     * Возвращает URL для скачивания файла
     */
    public function getDownloadUrl()
    {
        return $this->download_url;
    }

    public function getPermanentUrl()
    {
//        $token = Yii::$app->params['tokenAsana'];
//        $separator = strpos($this->permanent_url, '?') !== false ? '&' : '?';
//        $url = $this->permanent_url . $separator . 'token=' . urlencode($token);
//
//        return $this->getImageFromUrl($url);
        return $this->permanent_url;
    }

    protected function getImageFromUrl($url)
    {
        $token = Yii::$app->params['tokenAsana'];

        $ch = curl_init();

        // Устанавливаем URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Добавляем заголовок авторизации
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);

        // Выполняем запрос
        $imageData = curl_exec($ch);

        if (curl_errno($ch)) {
            // Логируем ошибку, если она возникла
            Yii::error('Ошибка cURL: ' . curl_error($ch));
            curl_close($ch);
            return null; // Возвращаем null в случае ошибки
        }

        curl_close($ch);

        return $imageData;
    }



    /*
     * Возвращает URL для просмотра файла
     */
    public function getViewUrl()
    {
        $token = Yii::$app->params['tokenAsana'];

        // Проверяем, есть ли уже параметры в URL (используется ? или &)
        $separator = strpos($this->view_url, '?') !== false ? '&' : '?';

        // Формируем URL с добавлением токена
        return $this->view_url . $separator . 'token=' . urlencode($token);
    }


    /*
     * Возвращает иконку для файла
     */
    public function getIcon()
    {
        $extension = $this->getExtension();
        $icon = '';
        switch ($extension) {
            case 'pdf':
                $icon = '<svg width="1em" height="1em">
                                            <use xlink:href="/vendor/bootstrap-icons/bootstrap-icons.svg#file-earmark-pdf-fill"></use>
                                        </svg>';
                break;
            case 'doc':
            case 'docx':
                $icon = '<svg width="1em" height="1em">
                                            <use xlink:href="/vendor/bootstrap-icons/bootstrap-icons.svg#file-earmark-word-fill"></use>
                                        </svg>';
                break;
            case 'xls':
            case 'xlsx':
                $icon = '<svg width="1em" height="1em">
                                            <use xlink:href="/vendor/bootstrap-icons/bootstrap-icons.svg#file-earmark-excel-fill"></use>
                                        </svg>';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $icon = '<svg width="1em" height="1em">
                                            <use xlink:href="/vendor/bootstrap-icons/bootstrap-icons.svg#file-earmark-image-fill"></use>
                                        </svg>';
                break;
            case 'zip':
            case 'rar':
                $icon = '<svg width="1em" height="1em">
                                            <use xlink:href="/vendor/bootstrap-icons/bootstrap-icons.svg#file-earmark-zip-fill"></use>
                                        </svg>';
                break;
            case 'mp3':
            case 'wav':
                $icon = '<svg width="1em" height="1em">
                                            <use xlink:href="/vendor/bootstrap-icons/bootstrap-icons.svg#file-earmark-music-fill"></use>
                                        </svg>';
                break;
            case 'mp4':
            case 'avi':
            case 'mov':
                $icon = '<svg width="1em" height="1em">
                                            <use xlink:href="/vendor/bootstrap-icons/bootstrap-icons.svg#file-earmark-play-fill"></use>
                                        </svg>';
                break;
                default:
                    $icon = '<svg width="1em" height="1em">
                                            <use xlink:href="/vendor/bootstrap-icons/bootstrap-icons.svg#file-earmark-text-fill"></use>
                                        </svg>';
                    break;
        }
        return $icon;
    }
}
