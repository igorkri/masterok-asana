<?php

namespace console\controllers;

use Asana;
use common\models\AsanaUsers;
use common\models\Project;
use common\models\Task;
use common\models\Timer;
use common\models\Workspace;
use SplFileObject;
use Yii;
use yii\db\Exception;
use yii\console\Controller;

class ImportController extends Controller
{

    // проверка на подключение к базе данных dbMs
    public function actionIndex()
    {

        $tasks = Task::find()->all();

        print_r($tasks);

//        $db = Yii::$app->dbms;
//
//        try {
//            $db->open();
//            echo 'Connection is successful';
//        } catch (Exception $e) {
//            echo 'Connection failed: ' . $e->getMessage();
//        }
    }


    /**
     * Импорт данных из csv файла в базу данных
     *
     * @param string $file
     */
    public function actionTime()
    {
        $file = Yii::getAlias('@console/runtime') . '/time_track_task.csv';

        if (!file_exists($file)) {
            echo 'File not found';
            return;
        }

        $fileObject = new SplFileObject($file);
        $fileObject->setFlags(SplFileObject::READ_CSV);

        foreach ($fileObject as $row) {
            if ($fileObject->key() === 0) { // Пропустить заголовок
                continue;
            }

//            print_r($row) . PHP_EOL; die();

            if (!empty($row)) {
                $taskId = $row[9] ?? null;
                if ($taskId) {
                    $task = Task::findOne(['gid' => "$taskId"]);
                    if ($task) {
                        $time = Timer::find()->where(['task_gid' => $task->gid, 'comment' => $row[8]])->one() ?: new Timer();
                        $time->task_gid = $task->gid;
                        $time->comment = $row[8];
                        $time->time = $row[3];
                        $time->minute = $row[4];
                        $time->coefficient = $row[5];
                        $time->created_at = date('Y-m-d H:i:s', $row[1]);
                        $time->updated_at = $row[7] ? date('Y-m-d H:i:s', $row[7]) : null;
                        $time->status = $this->getStatus($row[6]);
                        if ($time->save()) {
                            echo 'Task saved ' . $time->task_gid . PHP_EOL;
                        } else {
                            print_r($time->errors); die;
                        }
                    }
                } else {
                    echo 'Task not found ' . PHP_EOL;
                }
            } else {
                echo 'Empty row' . PHP_EOL;
            }
        }
    }


    private function getStatus($statusId)
    {
        switch ($statusId) {
            case 0:
                return Timer::STATUS_WAIT;
            case 1:
                return Timer::STATUS_PROCESS;
            case 2:
                return Timer::STATUS_PAID;
            case 3:
                return Timer::STATUS_INVOICE;
            case 4:
                return Timer::STATUS_PLANNED;
            case 5:
                return Timer::STATUS_NEED_CLARIFICATION;
            default:
                return Timer::STATUS_WAIT;
        }
    }

}
