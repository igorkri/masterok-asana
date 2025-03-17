<?php

namespace backend\widgets;

use common\models\Project;
use common\models\Task;
use yii\base\Widget;

class ActiveTaskWidget extends Widget
{

    public $date;

    public function run()
    {
        $taskCount = Task::find()
            ->where(['assignee_gid' => \Yii::$app->user->identity->user_asana_gid])
            ->count();
        $projects = Project::find()
            ->select(['project.*', 'COUNT(task.id) AS task_count']) // Выбираем все столбцы проекта + количество задач
            ->leftJoin('task', 'task.project_gid = project.gid') // Объединяем с таблицей задач
            ->where(['task.assignee_gid' => \Yii::$app->user->identity->user_asana_gid]) // Фильтруем по текущему пользователю
            ->groupBy('project.id') // Группируем по проекту
            ->orderBy(['task_count' => SORT_DESC]) // Сортируем по количеству задач (по убыванию)
            ->asArray()
            ->limit(8) // Ограничиваем 5 записями
            ->all();

        return $this->render('active-task', [
            'taskCount' => $taskCount,
            'projects' => $projects
        ]);
    }
}