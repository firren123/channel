<?php

return [
    'icp' => '',
    'tel' => '400-661-1690',
    'adminEmail'    => 'lichenjun@iyangpin.com',
    'cssVersion'    => '20150724',
    'jsVersion'     => '20150724',
    // 其他参数
    'pc_mall'       => 'http://test.i500m.com',                 //pc_mall 地址
    'imgHost'       => 'http://img.test.i500m.com/',   //图片域名地址
    'baseUrl'       => 'http://crm.test.i500m.com/',        //当前站点的URl
    'apiUrl'        => 'http://api.test.i500m.com/',          //测试地址
    'serverUrl'     => 'http://server.test.i500m.com/',       //测试地址商城网址

    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    'appId' => 'iyangpin_shop',
    'appKey' => 'UQJKSsa!@7AJk^&asDIFHn',
    'access_token'=>'fb54f3c5992b96d001bb16e8e92d968d',

    'fast_dfs' =>[
        'ip_addr'=>"118.186.247.55",
        'port'=>'23000',
        'sock'=>-1,
        'store_path_index'=>0,
    ],

    'COMPANY_ID' => 28,
    'code'        => require(__DIR__ . '/code.php'),
    'appId'       => 'I500_CHANNEL',
    'APP_CODE' => [
        'I500_SOCIAL' => 'DKJA@(SL)RssMAKDKas!L',
    ],
    'mobilePreg'      => '/^1[34587][0-9]{9}$/',      //Mobile 验证规则
    'token_timeout'   => 7*24*60*60,                  //用户登陆token有效期
    'verify_code_timeout'   => 60*60,            //用户短信验证码有效期
    'sign_debug'      => true,                       //false = 开启验证 true 关闭验证

    'baiduUrl'=>'http://api.map.baidu.com/geocoder/v2/',
    'suggestionUrl'=>'http://api.map.baidu.com/place/v2/suggestion',
    'suggestUrl'=>'http://apis.map.qq.com/ws/place/v1/suggestion/',
    'ak'=>'733971E4Af9f4b00c6839e27fec9b1a0',
    'qqak'=>'GDPBZ-4KURD-2U24D-P2JJM-NRGL6-MHFLQ',
    'icomet_url_admin' => 'http://channel.test.i500m.com/icometa/',

];

