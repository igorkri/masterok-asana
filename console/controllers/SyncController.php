<?php

namespace console\controllers;

use Asana;
use common\components\SyncAsana;
use common\models\Task;
use Yii;
use yii\console\Controller;
use yii\db\Expression;

class SyncController extends Controller
{
    /**
     * обновление задач с сайту на сервер Asana
     */
    public function actionUpdateTask()
    {
        SyncAsana::updateTask();
    }


    /**
     * Создание задачи в Asana
     */
    public function actionCreateTask()
    {
        SyncAsana::createTask();
    }

}
