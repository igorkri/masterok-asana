<?php

namespace frontend\controllers;


use Yii;
use yii\web\Controller;


class BlogController extends Controller
{
    public function actionView()
    {
        
        return $this->render('view');
        
    }

}
