<?php

namespace backend\widgets;

use yii\base\Widget;

class RecentReviewsWidget extends Widget
{

    public function run()
    {

        $taskStories = \common\models\TaskStory::find()
            ->where(['created_by_resource_type' => 'comment'])
            ->orWhere(['created_by_resource_type' => 'story'])
            ->groupBy('task_gid')
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->asArray()
            ->all();

//        debugDie($taskStories);


        return $this->render('recent-reviews',
            [
                'taskStories' => $taskStories
            ]
        );
    }
}