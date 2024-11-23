<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $version = '1.0.0';
    public $css = [
        "https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/regular.min.css",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/solid.min.css",
        "vendor/bootstrap/css/bootstrap.ltr.css",
        "vendor/highlight.js/styles/github.css",
        "vendor/simplebar/simplebar.min.css",
        "vendor/quill/quill.snow.css",
        "vendor/air-datepicker/css/datepicker.min.css",
        "vendor/select2/css/select2.min.css",
        "vendor/datatables/css/dataTables.bootstrap5.min.css",
        "vendor/nouislider/nouislider.min.css",
        "vendor/fullcalendar/main.min.css",
        "css/style.css"

    ];
    public $js = [
//        "vendor/jquery/jquery.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/regular.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/solid.min.js",
        "vendor/feather-icons/feather.min.js",
        "vendor/simplebar/simplebar.min.js",
        "vendor/bootstrap/js/bootstrap.bundle.min.js",
        "vendor/highlight.js/highlight.pack.js",
        "vendor/quill/quill.min.js",
        "vendor/air-datepicker/js/datepicker.min.js",
        "vendor/air-datepicker/js/i18n/datepicker.en.js",
        "vendor/select2/js/select2.min.js",
        "vendor/fontawesome/js/all.min.js",
        "vendor/chart.js/chart.min.js",
        "vendor/datatables/js/jquery.dataTables.min.js",
        "vendor/datatables/js/dataTables.bootstrap5.min.js",
        "vendor/nouislider/nouislider.min.js",
        "vendor/fullcalendar/main.min.js",
        "js/stroyka.js",
        "js/custom.js",
        "js/calendar.js",
        "js/demo.js",
        "js/demo-chart-js.js",
        'js/main.js',
        'js/ModalRemote.js',
        'js/ajaxcrud.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        // Добавляем версию к URL каждого ресурса
        foreach ($this->css as &$cssFile) {
            $cssFile .= '?v=' . $this->version;
        }

        foreach ($this->js as &$jsFile) {
            $jsFile .= '?v=' . $this->version;
        }
    }
}
