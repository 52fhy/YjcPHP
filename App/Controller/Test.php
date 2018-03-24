<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/3/27 027
 * Time: 21:19
 */

namespace App\Controller;


use Pheanstalk\Pheanstalk;
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


    public function producer(){
        $pheanstalk = new Pheanstalk('139.129.192.246');

        $pheanstalk
            ->useTube('testtube1')
            ->put("job payload goes here\n", 1024, 120);
    }

    public function consumer(){
        $pheanstalk = new Pheanstalk('139.129.192.246');
        $job = $pheanstalk
            ->watch('testtube1')
            ->ignore('default')
            ->reserve();

        echo $job->getData();

        $pheanstalk->delete($job);

// ----------------------------------------
// check server availability

        $pheanstalk->getConnection()->isServiceListening(); // true or false
    }

    public function hui(){
        $a = [
            [1,2,3,4],
            [5,6,7,8],
            [9,10,11,12]
        ];

        $row = count($a);
        $col = count($a[0]);

        $i=0;$j=0;
        $count = $row * $col;
        while ($count > 0){

            //打印最左边一列
            for($k=1;$k<$row; $k++){
                $count--;echo $a[$i][$j] .PHP_EOL;$i++;
            }

            //打印最下边一行
            for($k=1;$k<$col; $k++){
                $count--;echo $a[$i][$j].PHP_EOL;$j++;
            }

            //打印最右边一列
            for($k=1;$k<$row; $k++){
                $count--;echo $a[$i][$j].PHP_EOL;$i--;
            }

            //打印最上边一行
            for($k=1;$k<$col; $k++){
                $count--;echo $a[$i][$j].PHP_EOL;$j--;
            }

            $row-=2;
            $col-=2;
            $i++;
            $j++;

            if($row == 1){
                for($k=0;$k<$col; $k++){
                    $count--;echo $a[$i][$j].PHP_EOL;$j++;
                }
            }elseif($col == 1){
                for($k=0;$k<$row; $k++){
                    $count--;echo $a[$i][$j].PHP_EOL;$i++;
                }
            }
        }
    }
}