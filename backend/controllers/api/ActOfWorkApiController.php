<?php

namespace backend\controllers\api;

use Yii;
use common\models\ActOfWork;
use common\models\ActOfWorkDetail;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use backend\components\ApiKeyAuth;
use yii\filters\Cors;

/**
 * API контроллер для роботи з ActOfWork (Актами виконаних робіт)
 */
class ActOfWorkApiController extends Controller
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
     * Получить список актов
     * GET /api/act-of-work/list
     *
     * @return ActiveDataProvider
     */
    public function actionList()
    {
        $query = ActOfWork::find()
            ->with(['user', 'actOfWorkDetails'])
            ->orderBy(['created_at' => SORT_DESC]);

        // Фильтрация по параметрам
        if ($status = Yii::$app->request->get('status')) {
            $query->andWhere(['status' => $status]);
        }

        if ($type = Yii::$app->request->get('type')) {
            $query->andWhere(['type' => $type]);
        }

        if ($user_id = Yii::$app->request->get('user_id')) {
            $query->andWhere(['user_id' => $user_id]);
        }

        if ($period_year = Yii::$app->request->get('period_year')) {
            $query->andWhere(['period_year' => $period_year]);
        }

        if ($period_month = Yii::$app->request->get('period_month')) {
            $query->andWhere(['period_month' => $period_month]);
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
     * Получить один акт по ID
     * GET /api/act-of-work/view?id=1
     *
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $details = [];
        foreach ($model->actOfWorkDetails as $detail) {
            $details[] = [
                'id' => $detail->id,
                'task_gid' => $detail->task_gid,
                'project_gid' => $detail->project_gid,
                'project' => $detail->project,
                'task' => $detail->task,
                'description' => $detail->description,
                'amount' => $detail->amount,
                'hours' => $detail->hours,
                'created_at' => $detail->created_at,
                'updated_at' => $detail->updated_at,
            ];
        }

        return [
            'success' => true,
            'data' => [
                'id' => $model->id,
                'number' => $model->number,
                'status' => $model->status,
                'status_text' => ActOfWork::$statusList[$model->status] ?? null,
                'type' => $model->type,
                'type_text' => ActOfWork::$type[$model->type] ?? null,
                'period' => $model->period,
                'period_text' => $model->getPeriodText(),
                'period_type' => $model->period_type,
                'period_year' => $model->period_year,
                'period_month' => $model->period_month,
                'user_id' => $model->user_id,
                'user_name' => $model->user->username ?? null,
                'date' => $model->date,
                'description' => $model->description,
                'total_amount' => $model->total_amount,
                'paid_amount' => $model->paid_amount,
                'file_excel' => $model->file_excel,
                'telegram_status' => $model->telegram_status,
                'sort' => $model->sort,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
                'details' => $details,
            ]
        ];
    }

    /**
     * Создать новый акт
     * POST /api/act-of-work/create
     * Body: JSON с данными акта
     *
     * @return array
     */
    public function actionCreate()
    {
        $model = new ActOfWork();
        $data = Yii::$app->request->post();

        // Генерируем номер если не передан
        if (!isset($data['number']) || empty($data['number'])) {
            $data['number'] = ActOfWork::generateNumber();
        }

        if ($model->load($data, '') && $model->save()) {
            return [
                'success' => true,
                'message' => 'Акт успішно створено',
                'data' => [
                    'id' => $model->id,
                    'number' => $model->number,
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при створенні акту',
            'errors' => $model->errors,
        ];
    }

    /**
     * Обновить акт
     * PUT /api/act-of-work/update?id=1
     * Body: JSON с данными акта
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
                'message' => 'Акт успішно оновлено',
                'data' => [
                    'id' => $model->id,
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при оновленні акту',
            'errors' => $model->errors,
        ];
    }

    /**
     * Удалить акт
     * DELETE /api/act-of-work/delete?id=1
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

        // Удаляем все детали акта
        ActOfWorkDetail::deleteAll(['act_of_work_id' => $id]);

        if ($model->delete()) {
            return [
                'success' => true,
                'message' => 'Акт успішно видалено',
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при видаленні акту',
        ];
    }

    /**
     * Изменить статус акта
     * POST /api/act-of-work/change-status?id=1
     * Body: {"status": "paid"}
     *
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionChangeStatus($id)
    {
        $model = $this->findModel($id);
        $status = Yii::$app->request->post('status');

        if (!array_key_exists($status, ActOfWork::$statusList)) {
            return [
                'success' => false,
                'message' => 'Невірний статус',
            ];
        }

        $model->status = $status;

        if ($model->save(false)) {
            return [
                'success' => true,
                'message' => 'Статус акту успішно змінено',
                'data' => [
                    'status' => $model->status,
                    'status_text' => ActOfWork::$statusList[$model->status],
                ]
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка при зміні статусу акту',
        ];
    }

    /**
     * Получить статистику по актам
     * GET /api/act-of-work/statistics
     *
     * @return array
     */
    public function actionStatistics()
    {
        $status = Yii::$app->request->get('status');
        $type = Yii::$app->request->get('type');

        $query = ActOfWork::find();

        if ($status !== null) {
            $query->andWhere(['status' => $status]);
        }

        if ($type !== null) {
            $query->andWhere(['type' => $type]);
        }

        $totalRecords = $query->count();
        $totalAmount = $query->sum('total_amount') ?? 0;
        $totalPaidAmount = $query->sum('paid_amount') ?? 0;

        return [
            'success' => true,
            'data' => [
                'total_records' => $totalRecords,
                'total_amount' => round($totalAmount, 2),
                'total_paid_amount' => round($totalPaidAmount, 2),
                'unpaid_amount' => round($totalAmount - $totalPaidAmount, 2),
            ]
        ];
    }

    /**
     * Найти модель по ID
     *
     * @param int $id
     * @return ActOfWork
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = ActOfWork::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Акт не знайдено.');
    }
}

