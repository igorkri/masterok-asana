<?php

namespace console\controllers;

use Asana;
use common\components\SyncAsana;
use common\models\Task;
use Yii;
use yii\console\Controller;
use yii\db\Expression;

class TestController extends Controller
{

    public function actionCreateTask()
    {
        //print_r(Yii::$app->params['tokenAsana']);
        //SyncAsana::createTask();
    }


    /**
     * Подготовка до синхронизации задач на сервере Asana - (Обновление поля task_sync на update)
     */
    public function actionPrepareSync()
    {
//        $tasks = Task::find()->where(['task_sync' => Task::CRON_STATUS_UPDATE])->all();
        $tasks = Task::find()->where(['IS', 'task_sync', new Expression("NULL")])->all();
        foreach ($tasks as $task) {
            $task->task_sync = Task::CRON_STATUS_SUCCESS;
            $task->save();
        }
    }
}
