<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/3/27 027
 * Time: 21:19
 */

namespace App\Controller;


use Yjc\Helper\Amap;
use Yjc\Log;
use YJC\Picture;
use YJC\SingleLinkList;

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
        $head = new SingleLinkList();

        $head->add($head, new SingleLinkList('1', 'test'));
        $head->add($head, new SingleLinkList('5', 'test5'));
        $head->add($head, new SingleLinkList('2', 'test2'));
        $head->showList($head);

        $head->del($head, '53');

        $head->update($head, new SingleLinkList('2', 'jjjjj'));
        print_r($head);
        $head->showList($head);
    }
}