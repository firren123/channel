<?php

return [
    'name'=>'爱样品500M',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute'=>'site/index',
    'timeZone'=>'Asia/Chongqing',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ZIEF78AHhhkekr-wri32jgekjkwerkkjfwfwa',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => FALSE,
        ]
    ]
];
