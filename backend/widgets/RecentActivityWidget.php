<?php

namespace backend\widgets;

use yii\base\Widget;

class RecentActivityWidget extends Widget
{

    public function run()
    {

        $taskStories = \common\models\TaskStory::find()
            ->where(['created_by_resource_type' => 'system'])
            ->groupBy('task_gid')
            ->orderBy(['created_at' => SORT_DESC])->limit(8)
            ->asArray()
            ->all();

        return $this->render('recent-activity',
            [
                'taskStories' => $taskStories
            ]
        );
    }

}