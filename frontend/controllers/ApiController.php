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

    // В контроллере, например, WebhookController.php
    public function actionReceiveTask()
    {
        $request = Yii::$app->request;

        // Убедитесь, что запрос является POST
        if ($request->isPost) {
            $rawData = $request->rawBody;
            $taskData = json_decode($rawData, true);

            // Проверка, что данные успешно декодированы
            if (json_last_error() === JSON_ERROR_NONE) {
                // Логирование полученных данных для отладки
                $logFile = Yii::getAlias('@runtime/logs/task_received.log');
                file_put_contents($logFile, print_r($taskData, true), FILE_APPEND);

                // Возвращаем успешный ответ
                Yii::$app->response->statusCode = 200;
                return ['status' => 'success'];
            } else {
                // Неверный формат JSON
                Yii::$app->response->statusCode = 400;
                return ['status' => 'error', 'message' => 'Invalid JSON format'];
            }
        } else {
            // Неверный метод запроса
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Invalid request method'];
        }
    }



}