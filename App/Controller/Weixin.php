<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/6/25 025
 * Time: 21:12
 */

namespace App\Controller;


use EasyWeChat\Foundation\Application;

class Weixin extends Base
{
    public function index(){

        $app = new Application(config('wechat'));
        $notice = $app->notice;

        $userId = 'oe-d3tyzmMPsVH90Yu7bGbC9tVkU';
        $templateId = 'JHfvwTKsLRyb9INz_U5lDwwRFZuK5UJgg150oEVVexc';
        $url = 'http://overtrue.me';
        $data = array(
            "first"  => "恭喜你购买成功！",
            "name"   => "巧克力",
            "price"  => "39.8元",
            "remark" => "欢迎再次购买！",
        );
        //$result = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
        //var_dump($result);
    }

}