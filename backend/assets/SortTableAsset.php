<?php

namespace backend\assets;

use yii\web\AssetBundle;

class SortTableAsset  extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/sortable-widgets.css',
    ];

    public $js = [
        'js/jquery.binding.js',
        'js/Sortable.min.js',
        'js/sortable-widgets.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}