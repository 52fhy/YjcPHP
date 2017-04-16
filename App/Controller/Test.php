<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/3/27 027
 * Time: 21:19
 */

namespace App\Controller;


use Yjc\Helper\Amap;
use YJC\Picture;

class Test extends Base
{
    public function testPic(){

        $pic = new Picture(STATIC_PATH . '/test.jpg');
        $pic->zoom(100,100);
        $pic->sharp();
        $pic->show();
//        $pic->save_picture(STATIC_PATH .  '/test2.jpg');
    }

    public function t(){
        $res = Amap::gertRoadTraffic();
        dump($res);
    }
}