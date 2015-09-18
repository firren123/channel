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
        'social'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=i500_social',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
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
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
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
    ],

    'params' => $params,
];
