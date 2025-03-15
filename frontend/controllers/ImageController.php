<?php

namespace frontend\controllers;

use frontend\models\GenerateImage;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use ZipArchive;

class ImageController extends Controller
{
    public function actionGenerate()
    {


//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        $count = Yii::$app->request->get('count', 5);
//        $width = Yii::$app->request->get('width', 300);
//        $height = Yii::$app->request->get('height', 300);
//        $format = Yii::$app->request->get('format', 'jpg');
//        $query = Yii::$app->request->get('query', 'nature');
//
//        $validFormats = ['jpg', 'png', 'webp'];
//        if (!in_array($format, $validFormats)) {
//            return ['error' => 'Invalid format'];
//        }
//
//        $tempDir = Yii::getAlias('@runtime/images');
//        if (!file_exists($tempDir)) {
//            mkdir($tempDir, 0777, true);
//        }
//
//        $zipFile = $tempDir . '/images.zip';
//        $zip = new ZipArchive();
//        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
//            return ['error' => 'Cannot create ZIP file'];
//        }
//
//        for ($i = 0; $i < $count; $i++) {
//            $imageUrl = "https://source.unsplash.com/{$width}x{$height}/?{$query}";
//            $imagePath = "$tempDir/image_$i.$format";
//            file_put_contents($imagePath, file_get_contents($imageUrl));
//            $zip->addFile($imagePath, "image_$i.$format");
//        }
//
//        $zip->close();
//
//        return Yii::$app->response->sendFile($zipFile, 'images.zip')->on(Response::EVENT_AFTER_SEND, function ($event) use ($zipFile, $tempDir) {
//            unlink($zipFile);
//            array_map('unlink', glob("$tempDir/*.*"));
//            rmdir($tempDir);
//        });
    }

    public function actionCreate()
    {

        $model = new GenerateImage();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                return $model->generate();
            } else {
                return $model->errors;
            }
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
