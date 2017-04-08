<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/4/8 008
 * Time: 10:09
 */

namespace App\Controller;

use Hprose\Client;
use Hprose\Future;

class HproseClient extends Base
{

    public function index(){
        $client = Client::create('http://yjc_fw.cn/index.php/HproseServer');
        $var_dump = Future\wrap('print_r');

        $var_dump($client->test2());
    }

    public function index2(){
        $client = Client::create('http://yjc_fw.cn/index.php/HproseServer');
        $client->test1('yjc2')->then(function($result) {
            var_dump($result);
        });
    }

    public function sum(){
        $client = Client::create('http://yjc_fw.cn/index.php/HproseServer');
        $sum = $client->sum;
        $var_dump = Future\wrap('var_dump');

        $r1 = $sum(1, 3, 5, 7, 9);
        $r2 = $sum(2, 4, 6, 8, 10);
        $r3 = $sum($r1, $r2);
        $var_dump($r1, $r2, $r3);
    }

}