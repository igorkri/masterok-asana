<?php

namespace backend\controllers\api;

use Yii;
use common\models\Timer;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use backend\components\ApiKeyAuth;
use yii\filters\Cors;

/**
 * API контроллер для роботи з Timer
 */
class TimerApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Настройка CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        // API Key аутентификация (по умолчанию отключена)
        // Чтобы включить, установите в params.php: 'apiKey' => 'your-secret-key'
        $behaviors['apiKeyAuth'] = [
            'class' => ApiKeyAuth::class,
            'enabled' => false, // Включите при необходимости
        ];

        return $behaviors;
    }

    /**
     * Получить список таймеров
     * GET /api/timer/list
     *
     * @return ActiveDataProvider
     */
    public function actionList()
    {
        $query = Timer::find()
            ->with(['taskG', 'taskG.project'])
            ->orderBy(['created_at' => SORT_DESC]);

        // Фильтрация по параметрам
        if ($status = Yii::$app->request->get('status')) {
            $query->andWhere(['status' => $status]);
        }

        if ($archive = Yii::$app->request->get('archive')) {
            $query->andWhere(['archive' => $archive]);
        }

        if ($task_gid = Yii::$app->request->get('task_gid')) {
            $query->andWhere(['task_gid' => $task_gid]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per-page', 20),
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Получить один таймер по ID
     * GET /api/timer/view?id=1
     *
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return [
            'success' => true,
            'data' => [
                'id' => $model->id,
                'task_gid' => $model->task_gid,
                'task_name' => $model->taskG->name ?? null,
                'project_name' => $model->taskG->project->name ?? null,
                'time' => $model->time,
                'time_hour' => $model->getTimeHour(),
                'minute' => $model->minute,
                'coefficient' => $model->coefficient,
                'price' => $model->getCalcPrice(),
                'comment' => $model->comment,
                'status' => $model->status,
                'status_text' => Timer::$statusList[$model->status] ?? null,
                'archive' => $model->archive,
                'status_act' => $model->status_act,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
                'date_invoice' => $model->date_invoice,
                'date_report' => $model->date_report,
            ]
        ];
    }

    /**
     * Создать новый таймер
     * POST /api/timer/create
     * Body: JSON с данными таймера
     *
     * @return array
     */
    public function actionCreate()
    {
        $model = new Timer();
        $data = Yii::$app->request->post();

        if ($model->load($data, '') && $model->save()) {
            return [
                'success' => true,
                'message' => 'Таймер успішно створено',
                'data' => [
                    'id' => $model->id,
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при створенні таймера',
            'errors' => $model->errors,
        ];
    }

    /**
     * Обновить таймер
     * PUT /api/timer/update?id=1
     * Body: JSON с данными таймера
     *
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->getBodyParams();

        if ($model->load($data, '') && $model->save()) {
            return [
                'success' => true,
                'message' => 'Таймер успішно оновлено',
                'data' => [
                    'id' => $model->id,
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при оновленні таймера',
            'errors' => $model->errors,
        ];
    }

    /**
     * Удалить таймер
     * DELETE /api/timer/delete?id=1
     *
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            return [
                'success' => true,
                'message' => 'Таймер успішно видалено',
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при видаленні таймера',
        ];
    }

    /**
     * Получить статистику по таймерам
     * GET /api/timer/statistics
     *
     * @return array
     */
    public function actionStatistics()
    {
        $status = Yii::$app->request->get('status');
        $archive = Yii::$app->request->get('archive', Timer::ARCHIVE_NO);

        $query = Timer::find()
            ->where(['archive' => $archive]);

        if ($status !== null) {
            $query->andWhere(['status' => $status]);
        }

        $totalMinutes = $query->sum('minute') ?? 0;
        $totalPrice = 0;
        $totalRecords = $query->count();

        // Вычисляем общую стоимость
        $timers = $query->all();
        foreach ($timers as $timer) {
            $totalPrice += $timer->getCalcPrice();
        }

        return [
            'success' => true,
            'data' => [
                'total_records' => $totalRecords,
                'total_minutes' => $totalMinutes,
                'total_time' => Timer::getTotalTime($totalMinutes),
                'total_price' => round($totalPrice, 2),
            ]
        ];
    }

    /**
     * Архивировать/разархивировать таймер
     * POST /api/timer/toggle-archive?id=1
     *
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionToggleArchive($id)
    {
        $model = $this->findModel($id);
        $model->archive = $model->archive == Timer::ARCHIVE_NO ? Timer::ARCHIVE_YES : Timer::ARCHIVE_NO;

        if ($model->save(false)) {
            return [
                'success' => true,
                'message' => $model->archive == Timer::ARCHIVE_YES ? 'Таймер архівовано' : 'Таймер розархівовано',
                'data' => [
                    'archive' => $model->archive,
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при зміні статусу архіву',
        ];
    }

    /**
     * Найти модель по ID
     *
     * @param int $id
     * @return Timer
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Timer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Таймер не знайдено.');
    }
}

