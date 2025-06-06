<?php

namespace common\models;

use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\helpers\FileHelper;
use yii\web\Response;


/**
 * This is the model class for table "timer".
 *
 * @property int $id
 * @property string $task_gid
 * @property string $time
 * @property int $minute
 * @property float $coefficient
 * @property string|null $comment
 * @property int $status
 * @property int $archive
 * @property string $status_act
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $date_invoice
 * @property string|null $date_report
 * @property string|null $pks
 *
 * @property Task $taskG
 */
class Timer extends \yii\db\ActiveRecord
{

    /**
     * Статусы
     *
     * @var int
     *
     * Чекає на звіт
     * В процесі
     * Заплановано
     * Рахунок виставлено
     * Оплачено
     * Потребує уточнення
     */
    const STATUS_WAIT = 0;
    const STATUS_PROCESS = 1;
    const STATUS_PLANNED = 2;
    const STATUS_INVOICE = 3;
    const STATUS_PAID = 4;
    const STATUS_NEED_CLARIFICATION = 5;

    const STATUS_ACT_OK = 'ok'; //
    const STATUS_ACT_NOT_OK = 'not_ok'; // Не ок

    // стоимость за час
    const PRICE = 400;
    const COEFFICIENT_VALUE = 1.2;
    const ARCHIVE_NO = 0; // Не архівні
    const ARCHIVE_YES = 1; // Архівні

    public $price;
    public $hour;
    public $pks;
    public $project_id;
    public $exclude;

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Пересчитываем общее время и сохраняем его в задаче тбл. task_custom_field
        $total_minute = Timer::find()->where(['task_gid' => $this->task_gid, 'status' => self::STATUS_WAIT])->sum('minute');
        $task_custom_field = TaskCustomFields::find()->where(['task_gid' => $this->task_gid, 'custom_field_gid' => '1202687202895302'])->one();

        Yii::warning($total_minute, 'test ' . __METHOD__);

        if ($task_custom_field) {
            /* @var $task_custom_field TaskCustomFields */
            $task_custom_field->display_value = $total_minute;
            $task_custom_field->number_value = $total_minute;
            $task_custom_field->save();
        } else {
            $task_custom_field = new TaskCustomFields();
            $task_custom_field->task_gid = $this->task_gid;
            $task_custom_field->name = 'Час, факт.';
            $task_custom_field->type = 'number';
            $task_custom_field->custom_field_gid = '1202687202895302';
            $task_custom_field->display_value = $total_minute;
            $task_custom_field->number_value = $total_minute;
            $task_custom_field->save();
        }

        // Меняем статус задачи на "update"
        $task = Task::findOne(['gid' => $this->task_gid]);
        $task->task_sync = Task::CRON_STATUS_UPDATE;
        $task->save();
    }

    /**
     * @param int $total_minute
     *
     * @return float
     */
    public static function getPrice(int $total_minute, float $coefficient = 1.2)
    {
        // Учитываем коэффициент в стоимости
        return round((($total_minute / 60) * self::PRICE) * $coefficient, 2);
    }

    /**
     * Получаем стоимость с учетом коэффициента
     *
     * @return float
     */
    public function getCalcPrice(): float
    {
        // Учитываем коэффициент в стоимости
        return round(($this->getTimeHour() * self::PRICE) * $this->coefficient, 2);
    }

    /**
     * @param $data
     *
     * @return string
     * $data = [
     * 0 => '00:15:00'
     * 1 => '03:55:24'
     * 2 => '00:00:02'
     * 3 => '00:39:52'
     * 4 => '00:34:13'
     * 5 => '00:21:45'
     * 6 => '01:09:54'
     * 7 => '01:12:27'
     * 8 => '01:30:04'
     * 9 => '01:31:06'
     * 10 => '02:15:33'
     * 11 => '00:48:42'
     * 12 => '00:15:32'
     * 13 => '00:33:35'
     * 14 => '00:49:51'
     * 15 => '00:24:29'
     * 16 => '00:00:11'
     * 17 => '00:07:56'
     * 18 => '00:00:02'
     * 19 => '01:08:51'
     * ]
     *
     */
    public static function getTime($data): string
    {
        $total = 0;
        foreach ($data as $item) {
            list($hours, $minutes, $seconds) = explode(":", $item);
            $total += $hours * 3600 + $minutes * 60 + $seconds;
        }

        $hours = floor($total / 3600);
        $minutes = floor(($total % 3600) / 60);
        $seconds = $total % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    /**
     * Генерація даних для експорту в Excel
     *
     * поля для експорту:
     * 1. Назва задачі (taskG->name)
     * 2. Проект (taskG->project->name)
     * 3. Час (time)
     * 4. Коефіцієнт (coefficient)
     * 5. Ціна (price)
     * 6. Коментар (comment)
     * 7. Дата створення (created_at)
     * 8. Посилання на задачу (taskG->permalink_url)
     *
     * @param array $models
     *
     * @return string
     */
    public static function exportExcel(array $models, $prefix = '')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Заголовки колонок
        $headers = ['Назва задачі', 'Проект', 'Час', 'Коефіцієнт', 'Ціна', 'Коментар', 'Дата модифікації таксу', 'Посилання на задачу'];
        $sheet->fromArray([$headers], null, 'A1');

        // Форматирование заголовков
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(20);

        // Задание ширины колонок
        $columnWidths = ['A' => 60, 'F' => 60]; // Фиксированные ширины
        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
            $sheet->getStyle($col)->getAlignment()->setWrapText(true);
        }

        foreach (['B', 'C', 'D', 'E', 'G', 'H'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $row = 2;
        foreach ($models as $model) {
            /* @var $model Timer */
            $taskName = $model->taskG->name ?? '';
            $taskUrl = $model->taskG->permalink_url ?? '';
            $projectName = $model->taskG->project->name ?? '';
            $price = $model->getCalcPrice();
            $createdAt = $model->taskG->modified_at ? Yii::$app->formatter->asDatetime($model->taskG->modified_at, 'php:d.m.Y H:i:s') : '';
//            $createdAt = Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s');

            // Установка значений в строку
            $sheet->setCellValue("A$row", $taskName);
            $sheet->setCellValue("B$row", $projectName);
            $sheet->fromArray([$model->getTimeHour(), $model->coefficient, $price, $model->comment, $createdAt, $taskUrl], null, "C$row");

            // Гиперссылка на задачу
            if ($taskUrl) {
                $sheet->getCell("H$row")->getHyperlink()->setUrl($taskUrl);
                $sheet->getStyle("H$row")->applyFromArray(['font' => ['color' => ['rgb' => '0000FF']]]);
            }

            // Установка границ строки
            $sheet->getStyle("A$row:H$row")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $row++;
        }

        // Границы для всей таблицы
        $sheet->getStyle("A1:H" . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Сохранение в файл
        $date = $prefix . '-' . date('Y-m-d_H:i:s', time());
        $filePath = Yii::getAlias('@frontend/web/report/time/Звіт_' . $date . '.xlsx');
        $path = Yii::$app->request->hostInfo . '/report/time/Звіт_' . $date . '.xlsx';
        // Создаем папку для хранения
        FileHelper::createDirectory(dirname($filePath), 0777, true);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $path;
    }


    /**
     * Перебазовуємо time в години враховуючи години, хвилини та секунди
     *
     * @return float
     */
    public function getTimeHour($round = true): float
    {
        list($hours, $minutes, $seconds) = explode(":", $this->time);
        // Суммируем общее время
        $Hours = (int)$hours;
        $Minutes = (int)$minutes;
        $Seconds = (int)$seconds;

        // Если нужно округлить, то округляем до двух знаков после запятой
        if ($round) {
            return round($Hours + $Minutes / 60 + $Seconds / 3600, 2);
        }
        // Если не нужно округлять, то возвращаем без округления
        return $Hours + $Minutes / 60 + $Seconds / 3600;
    }



    /**
     * Подщитываем общее время в формате часы:минуты:секунды
     *
     * @param $minute
     *
     * @return string
     */
    public static function getTotalTime($minute)
    {
        $hours = floor($minute / 60); // Вычисляем количество часов
        $minutes = $minute % 60; // Остаток от деления на 60 — это минуты
        $seconds = 0; // В данном случае секунды отсутствуют

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }




    // Coefficient list
    static public array $coefficientList = [
        '0.0' => '0',
        '0.25' => '0.25',
        '0.5' => '0.5',
        '1.0' => '1.0',
        '1.2' => '1.2 (14.03.2025)',
        '1.5' => '1.5',
        '2.0' => '2.0',
        '2.5' => '2.5',
    ];

    static public array $statusList = [
        self::STATUS_WAIT => 'Чекає на звіт', // 0
        self::STATUS_PROCESS => 'В процесі', // 1
        self::STATUS_PLANNED => 'Заплановано', // 2
        self::STATUS_INVOICE => 'Копіювати в акти та згенерувати файл', // 3
        self::STATUS_PAID => 'Оплачено', // 4
//        self::STATUS_NEED_CLARIFICATION => 'Потребує уточнення', // 5
    ];

    // создаем beforeSave для поля minute который будет пересчитывать время в минуты из time (00:00:00)
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            } else {
                $this->updated_at = date('Y-m-d H:i:s');
            }

            // Парсим часы, минуты и секунды
            list($hours, $minutes, $seconds) = array_map('intval', explode(':', $this->time));

            // Считаем общее количество минут
            $this->minute = ($hours * 60) + $minutes;

            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'timer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_gid', 'time', 'minute', 'coefficient'], 'required'],
            [['status', 'time', 'created_at', 'updated_at', 'date_invoice', 'date_report', 'pks', 'project_id', 'exclude'], 'safe'],
            [['minute', 'archive'], 'integer'],
            [['coefficient'], 'number'],
            [['comment'], 'string'],
            [['task_gid'], 'string', 'max' => 255],
            [['status_act'], 'string', 'max' => 50],
            [['task_gid'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_gid' => 'gid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_gid' => 'Задача',
            'time' => 'Час',
            'minute' => 'Хвилини',
            'coefficient' => 'Коефіцієнт',
            'comment' => 'Коментар',
            'status' => 'Статус',
            'archive' => 'Архів',
            'status_act' => 'Статус акту виконаних робіт',
            'created_at' => 'Дата створення',
            'updated_at' => 'Дата оновлення',
            'price' => 'Ціна',
            'date_report' => 'Дата звіту',
            'date_invoice' => 'Дата виставлення рахунку',
        ];
    }

    /**
     * Gets query for [[TaskG]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskG()
    {
        return $this->hasOne(Task::class, ['gid' => 'task_gid']);
    }

    public function getStatusText(int $status)
    {
        return self::$statusList[$status];
    }

    public function getTotalPrice()
    {        // Если статус акта не ок, то цена 0
        return $this->getCalcPrice();
    }
}
