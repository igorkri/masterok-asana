<?php

namespace backend\controllers\report;

use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
use Yii;
use common\models\report\Invoice;
use backend\models\search\report\InvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\FileHelper;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex($page_type = Invoice::PAGE_TYPE_ALL)
    {    
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page_type' => $page_type,
        ]);
    }


    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Invoice #".$id,
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
     * Creates a new Invoice model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Invoice();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Створення Invoice",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Зберегти',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Створення Invoice",
                    'content'=>'<span class="text-success">Успішно створено!</span>',
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                            Html::a('Створити ще',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Створення Invoice",
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
     * Updates an existing Invoice model.
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
                    'title'=> "Редагування Invoice #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Зберегти',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Invoice #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Закрити',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                            Html::a('Редагувати',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Редагування Invoice #".$id,
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
     * Delete an existing Invoice model.
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
     * Delete multiple existing Invoice model.
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
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGenerateInvoices()
    {
        $request = Yii::$app->request;
        $date = date('Y-m-d', time());
        $outputPath = Yii::getAlias('@frontend/web/report/invoices/' . $date . '/invoices-' . $date . '.pdf');
        $path = Yii::$app->request->hostInfo . '/report/invoices/' . $date . '/invoices-' . $date . '.pdf';

        // Создаем директорию для хранения PDF
        FileHelper::createDirectory(dirname($outputPath), 0777, true);
        FileHelper::createDirectory(Yii::getAlias('@runtime/mpdf'), 0777, true);

        // Инициализация mPDF
        $pdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'tempDir' => Yii::getAlias('@runtime/mpdf'), // Указываем временную директорию
        ]);


        // Объединение контента для всех счетов
        $pks = explode(',', $request->post('pks'));
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);

            // Генерируем HTML для одной страницы
            $content = $this->renderPartial('_generate-invoice', ['model' => $model]);

            // Добавляем страницу в PDF
            $pdf->AddPage();
            $pdf->WriteHTML($content);
        }

        // Сохраняем единый PDF-файл
        $pdf->Output($outputPath, \Mpdf\Output\Destination::FILE);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'forceReload' => '#crud-datatable-pjax',
            'title' => "Завантаження рахунків",
            'content' => $this->renderAjax('download', [
                'type_doc' => Invoice::$pageTypeList[Invoice::PAGE_TYPE_INVOICE],
                'path' => $path,
            ]),
            'footer' => Html::button('Закрити', ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => "modal"])
        ];
    }



    /**
     * Генерация PDF-файла для актов
     *
     * @return array
     */
    public function actionGenerateActs()
    {
        $request = Yii::$app->request;
        $date = date('Y-m-d', time());
        $outputPath = Yii::getAlias('@frontend/web/report/acts/' . $date . '/acts-' . $date . '.pdf');
        $path = Yii::$app->request->hostInfo . '/report/acts/' . $date . '/acts-' . $date . '.pdf';

        // Создаем директорию для хранения PDF
        FileHelper::createDirectory(dirname($outputPath), 0777, true);
        FileHelper::createDirectory(Yii::getAlias('@runtime/mpdf'), 0777, true);

        // Инициализация mPDF
        $pdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'tempDir' => Yii::getAlias('@runtime/mpdf'), // Указываем временную директорию
        ]);

        // Объединение контента для всех актов
        $pks = explode(',', $request->post('pks'));
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);

            // Генерируем HTML для одной страницы
            $content = $this->renderPartial('_generate-act', ['model' => $model]);

            // Добавляем страницу в PDF
            $pdf->AddPage();
            $pdf->WriteHTML($content);
        }

        // Сохраняем единый PDF-файл
        $pdf->Output($outputPath, \Mpdf\Output\Destination::FILE);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'forceReload' => '#crud-datatable-pjax',
            'title' => "Завантаження актів",
            'content' => $this->renderAjax('download', [
                'type_doc' => Invoice::$pageTypeList[Invoice::PAGE_TYPE_ACT],
                'path' => $path,
            ]),
            'footer' => Html::button('Закрити', ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => "modal"])
        ];
    }

    /**
     * Генерация PDF-файла для рахунков и актов
     *
     * @return array
     */

    public function actionGenerateActsInvoices()
    {
        $request = Yii::$app->request;
        $date = date('Y-m-d', time());
        $outputPath = Yii::getAlias('@frontend/web/report/documents/' . $date . '/acts-invoices-' . $date . '.pdf');
        $path = Yii::$app->request->hostInfo . '/report/documents/' . $date . '/acts-invoices-' . $date . '.pdf';

        // Создаем папку для хранения PDF
        FileHelper::createDirectory(dirname($outputPath), 0777, true);

        // Инициализируем mPDF
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'tempDir' => Yii::getAlias('@runtime/mpdf'),
        ]);

        $pks = explode(',', $request->post('pks'));

        // Устанавливаем заголовок PDF
        $pdf->SetTitle("Рахунки та акти");
        //$pdf->SetHeader("Рахунок - Акт | " . date('d.m.Y H:i:s'));

        // *** Чередуем "Рахунок - Акт" ***
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);

            // --- Генерация Рахунку (Invoice) ---
            $invoiceContent = $this->renderPartial('_generate-invoice', ['model' => $model]);
            $pdf->AddPage();
            $pdf->WriteHTML("<h3 style='text-align: center;'>Рахунок-фактура №{$model->invoice_no}</h3>");
            $pdf->WriteHTML($invoiceContent);

            // --- Генерация Акта (Act) ---
            $actContent = $this->renderPartial('_generate-act', ['model' => $model]);
            $pdf->AddPage();
            $pdf->WriteHTML("<h3 style='text-align: center;'>Акт №{$model->act_no}</h3>");
            $pdf->WriteHTML($actContent);
        }

        // Сохраняем единый PDF-файл
        $pdf->Output($outputPath, \Mpdf\Output\Destination::FILE);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'forceReload' => '#crud-datatable-pjax',
            'title' => "Завантаження рахунків та актів",
            'content' => $this->renderAjax('download', [
                'type_doc' => 'Рахунки та акти',
                'path' => $path,
            ]),
            'footer' => Html::button('Закрити', ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => "modal"])
        ];
    }
}
