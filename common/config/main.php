<?php
$params = array_merge(

    require(__DIR__ . '/params.php')
);
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'id' => 'crm-backend',
    'language'=>'zh-CN',
    'name'=>'爱样品500M',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'defaultRoute'=>'admin/site/index',

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=shop',
            'username'=>'shop',
            'password'=>'shop',
            'charset'=>'utf8',
        ],
        'db_500m'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=500m_new',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
        ],
        'db_p500m'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=500m_new',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
            'attributes' => [PDO::ATTR_PERSISTENT => true]
        ],

        'mongodb' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://500m:trouCUs3hq1i@118.186.247.55:27017/shop',
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=118.186.247.55;port=9306;',
            'username' => '',
            'password' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\Admin',
            'enableAutoLogin' => true,

            //'isGuest'=>false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager'=> [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
//            'rules' => [
//                ['class' => 'yii\rest\UrlRule', 'controller' => ['user','shop']],
//            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ZZ9OX6n9bdunMW3iB-8IYdNGdJAnbMSp',
        ],
    ],

    'params' => $params,
];
