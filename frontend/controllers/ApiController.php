<?php


namespace frontend\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

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
        $behaviors = parent::behaviors();
        // Удаление проверки CSRF для API-запросов
        unset($behaviors['csrf']);

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }


//    public function beforeAction($action)
//    {
//        if ($action->id == 'receive-task') {
//            $this->enableCsrfValidation = false;
//        }
//        return parent::beforeAction($action);
//    }

    public function actionReceiveTask()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $rawData = $request->rawBody;
            $taskData = json_decode($rawData, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                // Логирование полученных данных в файл для отладки
                $logFile = Yii::getAlias('@runtime/logs/task_received.log');
                file_put_contents($logFile, print_r($taskData, true), FILE_APPEND);

                // Дополнительная обработка полученных данных

                // Возвращаем успешный ответ
                Yii::$app->response->statusCode = 200;
                return ['status' => 'success'];
            } else {
                // Логирование ошибки декодирования JSON
                Yii::error('Invalid JSON format: ' . json_last_error_msg());
                Yii::$app->response->statusCode = 400;
                return ['status' => 'error', 'message' => 'Invalid JSON format'];
            }
        } else {
            // Логирование неверного метода запроса
            Yii::error('Invalid request method');
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Invalid request method'];
        }
    }
}




}