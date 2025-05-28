<?php

namespace backend\controllers;

use common\models\ActOfWork;
use common\models\ActOfWorkDetail;
use common\models\Task;
use Yii;
use common\models\Timer;
use backend\models\search\TimerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * TimerController implements the CRUD actions for Timer model.
 */
class TimerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Timer models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new TimerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Timer model.
     * @param integer $id
     * @return mixed
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
     * Creates a new Timer model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_id)
    {
        $request = Yii::$app->request;
        $model = new Timer();
        $model->task_gid = $task_id;
        $model->status = Timer::STATUS_PLANNED;
        $model->coefficient = Timer::COEFFICIENT_VALUE;
        $model->created_at = date('Y-m-d H:i:s');
        $model->minute = 0;

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Створення Timer",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Зберегти',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
//                return [
//                    'forceReload'=>'#crud-datatable-pjax',
//                    'title'=> "Створення Timer",
//                    'content'=>'<span class="text-success">Успішно створено!</span>',
//                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
//                            Html::a('Створити ще',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
//
//                ];
            }else{           
                return [
                    'title'=> "Створення Timer",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Зберегти',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,

                ]);
            }
        }
       
    }

    /**
     * Updates an existing Timer model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Редагування Timer #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Зберегти',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
//                return [
//                    'forceReload'=>'#crud-datatable-pjax',
//                    'title'=> "Timer #".$id,
//                    'content'=>$this->renderAjax('view', [
//                        'model' => $model,
//                    ]),
//                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
//                            Html::a('Редагувати',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
//                ];
            }else{
                 return [
                    'title'=> "Редагування Timer #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Зберегти',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Timer model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

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
     * Delete multiple existing Timer model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
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
     * Finds the Timer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Timer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Timer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExportExcel()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' ));
        $path = self::generateFileExcel($pks);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'forceReload' => '#crud-datatable-pjax',
            'title' => "Завантаження звіту",
            'content' => $this->renderAjax('/report/invoice/download', [
                'type_doc' => 'excel',
                'path' => $path,
            ]),
            'footer' => Html::button('Закрити', ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => "modal"])
        ];

    }

    static function generateFileExcel($pks, $prefix = '')
    {
        $models = Timer::findAll($pks);
        return Timer::exportExcel($models, $prefix);
    }

    /*
     * Update status of Timer model
     *
     * @param integer $status
     *
     */
    public function actionUpdateStatus($status, $date_report = null)
    {
        // очищаем кеш
        Yii::$app->cache->flush();

        $request = Yii::$app->request;
        $post = $request->post();
        $pks = explode(',', $request->post( 'pks' ));
        if ($status == Timer::STATUS_INVOICE) {
//            Yii::warning($post, __METHOD__);
            $akt = new ActOfWork();
            $akt->number = ActOfWork::generateNumber();
            $akt->period = json_encode([$post['period_type'], $post['period_mount'], $post['period_year']]); // store period as JSON
            $akt->description = $post['comment'];
            $akt->status = ActOfWork::STATUS_PENDING;
            $akt->user_id = Yii::$app->user->id ?? 1;
            $akt->date = date('Y-m-d H:i:s');
            $akt->file_excel = null;
            $akt->total_amount = 0;
            $akt->paid_amount = 0;
            if ($akt->save()) {
                foreach ($pks as $pk) {
                    /** @var ActOfWorkDetail $detail */
                    $timer = Timer::find()->where(['id' => $pk])->one();
                    Yii::warning($timer, 'TimerController::actionUpdateStatus');
                    if (!$timer) {
                        Yii::error("Timer with ID $pk not found", __METHOD__);
                        continue;
                    }
                    // Проверяем, что timer отсутствует в акте
                    if ($actOfWorkDetail = ActOfWorkDetail::find()->where(['time_id' => $pk])->one()) {
                        $actOfWork = ActOfWork::findOne($actOfWorkDetail->act_of_work_id);
                        $log = new \common\models\ActWorkLog();
                        $log->act_of_work_id = $akt->id;
                        $log->act_of_work_detail_id = $actOfWorkDetail->id;
                        $log->timer_id = $pk;
                        $log->task_id = $actOfWorkDetail->task_gid; // Используем ID задачи из локальной базы данных
                        $log->project_id = $actOfWorkDetail->project_gid; // Используем ID проекта из локальной базы данных
                        $log->message = "Виявлено спробу додати час в акт (".ActOfWork::$monthsList[$post['period_mount']] .' '. ActOfWork::$yearsList[$post['period_year']] .' ('. ActOfWork::$periodTypeList[$post['period_type']]."))
                        але даний час вже існує в акті (". $actOfWork->getPeriodText() .")";
                        if (!$log->save()) {
                            Yii::error($log->errors, __METHOD__);
                        }
                        // Если таймер уже есть в акте, пропускаем его и пишем данные в отдельный лог файл act-detail.log
                        Yii::info("Таймер з ID $pk Вже існує в ActOfworkDetail з ID {$actOfWorkDetail->id}",'application.exportact');
                        continue;
                    }

                    // Получаем связанную запись Task из базы данных по task_gid
                    $task = Task::find()->where(['gid' => $timer->task_gid])->one();
                    if (!$task) {
                        Yii::error("Task with GID {$timer->task_gid} not found", __METHOD__);
                        continue;
                    }

                    // Получаем связанный проект
                    $project = $task->project;
                    if (!$project) {
                        Yii::error("Project not found for task with ID {$task->id}", __METHOD__);
                        continue;
                    }

                    $detail = new ActOfWorkDetail();
                    $detail->act_of_work_id = $akt->id;
                    $detail->time_id = $pk;
                    $detail->task_gid = $task->id; // Используем ID задачи из локальной базы данных
                    $detail->project_gid = $project->id; // Используем ID проекта из локальной базы данных
                    $detail->project = $project->name ?? '⸺';
                    $detail->task = $task->name ?? '⸺';
                    $detail->description = $timer->comment;
                    $detail->amount = $timer->getCalcPrice();
                    $detail->hours = $timer->getTimeHour();
                    if (!$detail->save()) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::error('Failed to save ActOfWorkDetail: ' . json_encode($detail->getErrors()), __METHOD__);
                        return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
                    }
                    $timer->status_act = Timer::STATUS_ACT_OK;
                    $timer->save(false);
                }
                $prefix = ActOfWork::$periodTypeList[$post['period_type']] .'_'. ActOfWork::$monthsList[$post['period_mount']] .'_'. ActOfWork::$yearsList[$post['period_year']];
                $akt->file_excel = self::generateFileExcel($pks, $prefix);
                $akt->total_amount = ActOfWorkDetail::find()
                    ->where(['act_of_work_id' => $akt->id])
                    ->sum('amount');
                $akt->paid_amount = 0; // TODO: implement paid amount logic
                if (!$akt->save()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::error('Failed to save ActOfWork: ' . json_encode($akt->getErrors()), __METHOD__);
                    return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
                }
                $this->pks($pks, $status);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::error('Failed to save ActOfWork: ' . json_encode($akt->getErrors()), __METHOD__);
                return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
            }
        }
        //$this->pks($pks, $status);
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }


    protected function pks($pks, $status)
    {
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->status = $status;
            $model->date_invoice = date('Y-m-d H:i:s');
            $model->save();
        }
    }

    /**
     * Advanced filter for Timer model
     */
    public function actionAdvancedFilter()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $searchModel = new TimerSearch();
        $selected = Yii::$app->session->has('advanced-filter') ? Yii::$app->session->get('advanced-filter') : ['projectIds' => [], 'exclude' => 'no'];

        return [
            'title' => "Розширені фільтри",
            'content' => $this->renderAjax('_advanced-filter', [
                'searchModel' => $searchModel,
                'selected' => $selected,
            ]),
            'footer' => Html::button('Закрити', ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => "modal"]) .
                Html::a('Закрити та скинути фільтри', 'remove-advanced-filter', ['class' => 'btn btn-default pull-left'])
        ];
    }

    /**
     * Remove advanced filter for Timer model
     */
    public function actionRemoveAdvancedFilter()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        Yii::$app->session->remove('advanced-filter');
        return $this->redirect(['index']); // можно добавить 'TimerSearch' => [] если хочешь сбросить вручную
    }

}
