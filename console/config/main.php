<?php
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
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
        'db_pay'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=pay',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=9306;',
            'username' => '',
            'password' => '',
        ],
    ],
   // 'params' => $params,
];
