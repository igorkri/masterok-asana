<?php


namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionReceiveTask()
    {

        $data = Yii::$app->request->rawBody;
        $taskData = json_decode($data, true);

        // Запись данных задачи в файл для отладки
        $logFile = Yii::getAlias('@runtime/logs/task_received.log');
        $logEntry = "Received Task Data: " . date('Y-m-d H:i:s') . "\n" . print_r($taskData, true) . "\n\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        // Ваш код для дальнейшей обработки данных задачи

        return ['status' => 'success'];
    }


}