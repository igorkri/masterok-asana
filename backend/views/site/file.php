<?php

use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Url;
use yii\web\JsExpression;

?>


<div id="top" class="sa-app__body">
    <div class="mx-xxl-3 px-4 px-sm-5">
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="<?= Url::to(['/site/index']) ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0"><?= $this->title ?></h1>
                </div>
                <div class="col-auto d-flex">
                </div>
            </div>
        </div>
    </div>
    <div class="mx-xxl-3 px-4 px-sm-5 pb-6">
        <div class="sa-layout">
            <div class="sa-layout__backdrop" data-sa-layout-sidebar-close=""></div>
            <?php //echo $this->render('_filter-project', ['project' => $project, 'searchModel' => $searchModel]) ?>
            <div class="sa-layout__content">
                <div class="card">
                    <?php
                    echo ElFinder::widget([
                        'language' => 'ru',
                        'controller' => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
                        //'filter'           => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
                        //'callbackFunction' => new JsExpression('function(file, id){}') // id - id виджета
                        'callbackFunction' => new JsExpression('function(file, id) {
        console.log(file.url); // Здесь вы можете обработать URL выбранного файла
    }'),
                        'containerOptions' => ['style' => 'width: 100%; height: 700px; border: 1px solid #ccc;'], // Опции для контейнера
                    ]);

                    ?>

                </div>
            </div>
        </div>
    </div>
