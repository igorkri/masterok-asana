<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'access'],
    'language' => 'uk-UA',
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
//        'treemanager' =>  [
//            'class' => '\igorkri\tree\Module',
//            // other module settings, refer detailed documentation
//            'i18n' => [
//                'class' => 'yii\i18n\PhpMessageSource',
//                'basePath' => '@backend/messages',
//                'forceTranslation' => true
//            ]
//        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'backend\assets\AppAsset' => [
                    'class' => 'backend\assets\AppAsset',
                    'version' => time(), // Set the version dynamically
                ],
                'nullref\datatable\assets\DataTableAsset' => [
                    'styling' => \nullref\datatable\assets\DataTableAsset::STYLING_JUI,
                ],
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],
        'access' => [
            'class' => 'yii\filters\AccessControl',
            'denyCallback' => function ($rule, $action) {
                Yii::$app->response->redirect(['/site/login'])->send();
                Yii::$app->end();
            },
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST webhook-handler' => 'asana-webhook/webhook-handler',
                'GET webhook-handler' => 'asana-webhook/webhook-handler',
            ],
        ],

    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'igorkri\elfinder\PathController',
//            'class' => 'mihaildev\elfinder\PathController',
            'plugin' => [
                [
                    'class' => '\igorkri\elfinder\plugin\Sluggable',
                    'lowercase' => true,
                    'replacement' => '-'
                ]
            ],
            'connectOptions' => [
                'debug' => true,
            ],
            'access' => ['@'],
            'root' => [
                'baseUrl' => '@web',
                'basePath' => '@frontend/web',
//                'path' => '/',
                'path' => '/report',
                'name' => 'Каталог',
            ],
        ],
    ],
    'params' => $params,
];
