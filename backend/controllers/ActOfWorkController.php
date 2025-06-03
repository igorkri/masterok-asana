<?php

namespace backend\controllers;

use backend\models\search\ActOfWorkDetailSearch;
use common\models\ActOfWork;
use backend\models\search\ActOfWorkSearch;
use common\models\Timer;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ActOfWorkController implements the CRUD actions for ActOfWork model.
 */
class ActOfWorkController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ActOfWork models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;

//        debugDie($this->request->queryParams);
        $bulkHide = Yii::$app->request->get('bulk-hide');

        if (Yii::$app->request->isPost) {
            // Если запрос POST, сохраняем Id в сессии
            $pks = Yii::$app->request->post('pks', []);
            if (!empty($pks)) {
                $session->set('bulk-hide', $pks);
            }
        }
        if ($bulkHide == 'clear') {
            $session->remove('bulk-hide');
        }

        $searchModel = new ActOfWorkSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if(Yii::$app->request->isAjax){
            $this->redirect(['index']);
        }

        $timers = Timer::find()
            ->where(['status' => [Timer::STATUS_WAIT, Timer::STATUS_PROCESS, Timer::STATUS_PLANNED, Timer::STATUS_NEED_CLARIFICATION]])
            ->andWhere(['status_act' => Timer::STATUS_ACT_NOT_OK])
            ->all();
        $timerTotalPrice = 0;
        if (!empty($timers)) {
            foreach ($timers as $timer) {
                /** @var $timer Timer */
                $timerTotalPrice += $timer->getTotalPrice();
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'timerTotalPrice' => $timerTotalPrice,
        ]);
    }

    /**
     * Displays a single ActOfWork model.
     * @param int $id ID
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'=> "Timer #".$id,
                'content'=>$this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                    Html::a('Редагувати',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new ActOfWork model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ActOfWork();
        $model->number = time();
        $model->date = date("Y-m-d");
        $model->user_id = Yii::$app->user->identity->id ?? 1;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            } else {
                debugDie($model->getErrors());
                Yii::$app->session->setFlash('error', 'Помилка при створенні акту. Будь ласка, перевірте введені дані.');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ActOfWork model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new ActOfWorkDetailSearch();
        $searchModel->act_of_work_id = $model->id; // Set the act_of_work_id for the search model
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination->pageSize = false; // Set a default page size for the data provider

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Акт успішно оновлено.');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing ActOfWork model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionBulkdelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }

    }

    /**
     * Finds the ActOfWork model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ActOfWork the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActOfWork::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSendTelegram()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            if ($model->telegram_status == ActOfWork::TELEGRAM_STATUS_SEND) {
                Yii::$app->session->setFlash('error', 'Акт уже був надісланий в Telegram.');

                continue;
            }
            if ($model->telegram_status == ActOfWork::TELEGRAM_STATUS_FAILED) {
                Yii::$app->session->setFlash('error', 'Акт не може бути надісланий, оскільки попередня спроба завершилася невдачею.');
                continue;
            }
            if ($model->sendTelegram()) {
                Yii::$app->session->setFlash('success', 'Акт успішно надіслано в Telegram.');
            } else {
                Yii::$app->session->setFlash('error', 'Помилка при надсиланні акту в Telegram: ' . implode(', ', $model->getErrorSummary(true)));
            }
        }

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }
}
