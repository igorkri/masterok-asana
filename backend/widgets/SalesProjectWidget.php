<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\db\Query;
use Yii;

class SalesProjectWidget extends Widget
{
    public function run()
    {
        $query = (new Query())
            ->select([
                'project_name' => 'p.name',
                'task_count' => 'COUNT(DISTINCT t.id)',
                'total_sum' => 'ROUND(SUM(IFNULL(ti.minute, 0) / 60 * ti.coefficient * 400), 2)',
            ])
            ->from('timer ti')
            ->innerJoin('task t', 'ti.task_gid = t.gid')
            ->innerJoin('project p', 't.project_gid = p.gid')
            ->groupBy('p.gid')
            ->orderBy(['total_sum' => SORT_DESC])
            ->limit(5);

        $data = Yii::$app->db->createCommand($query->createCommand()->getRawSql())->queryAll();

        $colors = ['#FF0F42', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];

        // Добавляем цвета в исходный массив $data
        foreach ($data as $index => &$row) {
            $row['color'] = $colors[$index % count($colors)];
        }
        unset($row); // Разрываем ссылку, чтобы избежать неожиданных багов

        $labels = [];
        /**
           [
                {"label":"Yandex","value":2742.00,"color":"#ffd333","orders":12},
                {"label":"YouTube","value":3272.00,"color":"#e62e2e","orders":51},
                {"label":"Google","value":2303.00,"color":"#3377ff","orders":4},
                {"label":"Facebook","value":1434.00,"color":"#29cccc","orders":10},
                {"label":"Instagram","value":799.00,"color":"#5dc728","orders":1}
            ]
         */
        // меняем ключи массива с project_name на labels, total_sum на value, task_count на count
        foreach ($data as $index => &$row) {
            $labels[] = [
                'label' => $row['project_name'],
                'value' => $row['total_sum'],
                'color' => $row['color'],
                'orders' => $row['task_count']
            ];
        }
        // Формируем JSON для графика
        $jsonResult = json_encode($labels, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);

        return $this->render('sales-project', [
            'salesProject' => $data,  // Теперь содержит 'color'
            'jsonResult' => $jsonResult
        ]);
    }
}
