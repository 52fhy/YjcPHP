<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 12:24
 */

return array(
    'url_type' => 1, //0 普通模式 http://localhost/?c=user&a=login&var=value
                      //1 PATHINFO http://localhost/index.php/user/login/var/value/
    'output_type' => 'json', //template,json,xml,hprose,pure,msgpack
    'decorator' => [
        //'App\Decorator\Template',
        //'App\Decorator\Json',
    ],
    'log' => [
        'file' => BASE_PATH . '/logs/'.date('Ymd').'_access.log',
        'level' => 'debug',
        'handler' => '',
    ]
);