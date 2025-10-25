<?php

namespace backend\controllers\api;

use Yii;
use common\models\ActOfWorkDetail;
use common\models\ActOfWork;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use backend\components\ApiKeyAuth;
use yii\filters\Cors;

/**
 * API контроллер для роботи з ActOfWorkDetail (Деталями актів виконаних робіт)
 */
class ActOfWorkDetailApiController extends Controller
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
     * Получить список деталей актов
     * GET /api/act-of-work-detail/list
     *
     * @return ActiveDataProvider
     */
    public function actionList()
    {
        $query = ActOfWorkDetail::find()
            ->with(['actOfWork', 'time', 'task0', 'project0'])
            ->orderBy(['created_at' => SORT_DESC]);

        // Фильтрация по параметрам
        if ($act_of_work_id = Yii::$app->request->get('act_of_work_id')) {
            $query->andWhere(['act_of_work_id' => $act_of_work_id]);
        }

        if ($task_gid = Yii::$app->request->get('task_gid')) {
            $query->andWhere(['task_gid' => $task_gid]);
        }

        if ($project_gid = Yii::$app->request->get('project_gid')) {
            $query->andWhere(['project_gid' => $project_gid]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }

    /**
     * Получить одну деталь акта по ID
     * GET /api/act-of-work-detail/view?id=1
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
                'act_of_work_id' => $model->act_of_work_id,
                'act_number' => $model->actOfWork->number ?? null,
                'time_id' => $model->time_id,
                'task_gid' => $model->task_gid,
                'project_gid' => $model->project_gid,
                'project' => $model->project,
                'task' => $model->task,
                'description' => $model->description,
                'amount' => $model->amount,
                'hours' => $model->hours,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ]
        ];
    }

    /**
     * Создать новую деталь акта
     * POST /api/act-of-work-detail/create
     * Body: JSON с данными детали акта
     *
     * @return array
     */
    public function actionCreate()
    {
        $model = new ActOfWorkDetail();
        $data = Yii::$app->request->post();

        if ($model->load($data, '') && $model->save()) {
            // Обновляем общую сумму акта
            $this->updateActTotalAmount($model->act_of_work_id);

            return [
                'success' => true,
                'message' => 'Деталь акту успішно створено',
                'data' => [
                    'id' => $model->id,
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при створенні деталі акту',
            'errors' => $model->errors,
        ];
    }

    /**
     * Обновить деталь акта
     * PUT /api/act-of-work-detail/update?id=1
     * Body: JSON с данными детали акта
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
            // Обновляем общую сумму акта
            $this->updateActTotalAmount($model->act_of_work_id);

            return [
                'success' => true,
                'message' => 'Деталь акту успішно оновлено',
                'data' => [
                    'id' => $model->id,
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при оновленні деталі акту',
            'errors' => $model->errors,
        ];
    }

    /**
     * Удалить деталь акта
     * DELETE /api/act-of-work-detail/delete?id=1
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
        $act_of_work_id = $model->act_of_work_id;

        if ($model->delete()) {
            // Обновляем общую сумму акта
            $this->updateActTotalAmount($act_of_work_id);

            return [
                'success' => true,
                'message' => 'Деталь акту успішно видалено',
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при видаленні деталі акту',
        ];
    }

    /**
     * Получить детали акта по ID акта
     * GET /api/act-of-work-detail/by-act?act_id=1
     *
     * @param int $act_id
     * @return array
     */
    public function actionByAct($act_id)
    {
        $details = ActOfWorkDetail::find()
            ->where(['act_of_work_id' => $act_id])
            ->with(['time', 'task0', 'project0'])
            ->all();

        $data = [];
        $totalAmount = 0;
        $totalHours = 0;

        foreach ($details as $detail) {
            $data[] = [
                'id' => $detail->id,
                'time_id' => $detail->time_id,
                'task_gid' => $detail->task_gid,
                'project_gid' => $detail->project_gid,
                'project' => $detail->project,
                'task' => $detail->task,
                'description' => $detail->description,
                'amount' => $detail->amount,
                'hours' => $detail->hours,
            ];
            $totalAmount += $detail->amount;
            $totalHours += $detail->hours;
        }

        return [
            'success' => true,
            'data' => $data,
            'summary' => [
                'total_records' => count($details),
                'total_amount' => round($totalAmount, 2),
                'total_hours' => round($totalHours, 2),
            ]
        ];
    }

    /**
     * Получить статистику по деталям актов
     * GET /api/act-of-work-detail/statistics
     *
     * @return array
     */
    public function actionStatistics()
    {
        $act_of_work_id = Yii::$app->request->get('act_of_work_id');

        $query = ActOfWorkDetail::find();

        if ($act_of_work_id !== null) {
            $query->andWhere(['act_of_work_id' => $act_of_work_id]);
        }

        $totalRecords = $query->count();
        $totalAmount = $query->sum('amount') ?? 0;
        $totalHours = $query->sum('hours') ?? 0;

        return [
            'success' => true,
            'data' => [
                'total_records' => $totalRecords,
                'total_amount' => round($totalAmount, 2),
                'total_hours' => round($totalHours, 2),
            ]
        ];
    }

    /**
     * Обновить общую сумму акта
     *
     * @param int $act_of_work_id
     * @return void
     */
    protected function updateActTotalAmount($act_of_work_id)
    {
        $act = ActOfWork::findOne($act_of_work_id);
        if ($act) {
            $totalAmount = ActOfWorkDetail::find()
                ->where(['act_of_work_id' => $act_of_work_id])
                ->sum('amount') ?? 0;

            $act->total_amount = $totalAmount;
            $act->save(false);
        }
    }

    /**
     * Найти модель по ID
     *
     * @param int $id
     * @return ActOfWorkDetail
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = ActOfWorkDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Деталь акту не знайдено.');
    }
}

