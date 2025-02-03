<?php

namespace backend\widgets;

use common\models\Timer;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class ChartWidget extends Widget
{

    public $data;

    public function run()
    {
        $result = [];
        $months = [
            1 => 'Січень',
            2 => 'Лютий',
            3 => 'Березень',
            4 => 'Квітень',
            5 => 'Травень',
            6 => 'Червень',
            7 => 'Липень',
            8 => 'Серпень',
            9 => 'Вересень',
            10 => 'Жовтень',
            11 => 'Листопад',
            12 => 'Грудень',

        ];
        foreach ($months as $key => $month) {
            // Определяем начало и конец месяца
            $dateBegin = date('Y-m-01', strtotime(date('Y') . '-' . $key . '-01'));
            $dateEnd = date('Y-m-t', strtotime($dateBegin));

            // Получаем данные из Timer
            $timers = Timer::find()
                ->select(['minute', 'coefficient'])
                ->where(['between', 'created_at', $dateBegin, $dateEnd])
                ->asArray()
                ->all();

            // Подсчет общей стоимости работы за месяц
            $totalCost = 0;
            foreach ($timers as $timer) {
                $minutes = $timer['minute'];
                $coefficient = $timer['coefficient'];

                // Учитываем коэффициент для каждой записи
                $totalCost += ($minutes / 60) * Timer::PRICE * $coefficient;
            }

            // Округляем сумму
            $totalCost = round($totalCost, 2);

            // Добавляем в результат
            $result[] = [
                'label' => $month,
                'value' => $totalCost
            ];
        }

        // Преобразуем массив в JSON без экранирования слэшей и юникода
        $this->data = json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);


//        debugDie($this->date);

        return $this->render('chart', [
            'data' => $this->data,
        ]);
    }
}