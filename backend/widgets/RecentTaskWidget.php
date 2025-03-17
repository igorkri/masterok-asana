<?php

namespace backend\widgets;

use yii\base\Widget;

class RecentTaskWidget extends Widget
{

    public function run()
    {
        $tasks = (new \yii\db\Query())
            ->select([
                't.gid as task_gid',
                't.name as task_name',
                't.section_project_gid as section_project_gid',
                't.created_at as task_created_at',
                'au.name as assignee_name',
                'sp.name as section_project_name',
                'ROUND(SUM(IFNULL(ti.minute, 0) / 60 * ti.coefficient), 2) as total_time',
            ])
            ->from('task t')
            ->innerJoin('asana_users au', 't.assignee_gid = au.gid')
            ->innerJoin('section_project sp', 't.section_project_gid = sp.gid')
            ->leftJoin('timer ti', 't.gid = ti.task_gid')
            ->groupBy(['t.gid', 't.name', 't.created_at', 'au.name', 'sp.name']) // Добавляем GROUP BY
            ->orderBy(['t.created_at' => SORT_DESC])
            ->limit(12)
            ->all();

        return $this->render('recent-task', [
            'tasks' => $tasks,
        ]);
    }

}