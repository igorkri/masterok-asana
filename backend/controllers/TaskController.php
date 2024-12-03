<?php

namespace backend\controllers;

use common\models\Project;
use common\models\TaskAttachment;
use Yii;
use common\models\Task;
use backend\models\search\TaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\ServerErrorHttpException;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex($project_gid = null)
    {
        $searchModel = new TaskSearch();
        $searchModel->project_gid = $project_gid;
        $dataProvider = $searchModel->search($this->request->queryParams);

        $project = Project::findOne(['gid' => $project_gid]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'project' => $project,
        ]);
    }


    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Task #".$id,
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
     * Creates a new Task model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Task();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Створення Task",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Зберегти',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Створення Task",
                    'content'=>'<span class="text-success">Успішно створено!</span>',
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                            Html::a('Створити ще',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Створення Task",
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
     * Updates an existing Task model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($gid)
    {
        $request = Yii::$app->request;
        $model = Task::findOne(['gid' => $gid]);

        if($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->load($request->post()) && $model->save()) {
                return [
                    'success' => true,
                    'toast' => [
                        'class' => 'toast-sa-success',
                        'name' => "Task #" . $model->gid,
                        'message' => 'Успішно оновлено!',
                    ],
                    'html' => $this->renderAjax('_update-form', [
                        'model' => $model,
                    ]),
                ];
            } else {
                $message = '';
                foreach ($model->getErrors() as $errors) {
                    foreach ($errors as $error) {
                        $message .= $error . '<br>';
                    }
                }

                return [
                    'success' => false,
                    'toast' => [
                        'class' => 'toast-sa-danger',
                        'name' => "Task #" . $model->gid,
                        'message' => $message,
                    ],
                ];
            }

        }

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['update', 'gid' => $model->gid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Delete an existing Task model.
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
     * Delete multiple existing Task model.
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
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImage($gid)
    {
        $model = TaskAttachment::findOne(['gid' => $gid]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title'=> "Файл " . $model->name,
            'content'=>$this->renderAjax('_image', [
                'model' => $model,
            ]),
            'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                Html::button('Зберегти',['class'=>'btn btn-primary','type'=>"submit"])

        ];
    }

    public function actionGetImage($gid)
    {
        // Находим вложение по gid
        $attachment = TaskAttachment::findOne(['gid' => $gid]);
        if (!$attachment) {
            throw new NotFoundHttpException('Attachment not found.');
        }

        // Получаем URL для скачивания изображения
        $downloadUrl = $attachment->getPermanentUrl();
        if ($downloadUrl === null) {
            throw new ServerErrorHttpException('Unable to fetch the image URL.');
        }

        // Инициализируем cURL для получения данных изображения
        $ch = curl_init($downloadUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $imageData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Проверяем успешность получения изображения
        if ($httpCode !== 200 || $imageData === false) {
            Yii::error("cURL error: $curlError", __METHOD__);
            throw new ServerErrorHttpException('Unable to fetch the image.');
        }

        // Устанавливаем заголовок типа контента (определяем тип по URL или заголовкам ответа)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $contentType = finfo_buffer($finfo, $imageData);
        finfo_close($finfo);
        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . strlen($imageData));

        // Очищаем буфер вывода и выводим данные изображения
        if (ob_get_length()) {
            ob_end_clean();
        }

        echo $imageData;
        exit();
    }




}
