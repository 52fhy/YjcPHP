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

    /**
     * 异步调用
     * https://github.com/hprose/hprose-php/wiki/05.-Hprose-%E5%AE%A2%E6%88%B7%E7%AB%AF
     */
    public function index(){
        $client = Client::create('http://yjc_fw.cn/index.php/HproseServer');
        $var_dump = Future\wrap('print_r');

        $var_dump($client->test2());
    }

    public function index2(){
        $client = Client::create('http://yjc_fw.cn/index.php/HproseServer');
        $client->test1('yjc2')->then(function($result) {//异步调用
            var_dump($result);
        });
    }

    public function sum(){
        $client = Client::create('http://yjc_fw.cn/index.php/HproseServer');
        $sum = $client->sum;
        $var_dump = Future\wrap('var_dump');//异步调用

        $r1 = $sum(1, 3, 5, 7, 9);
        $r2 = $sum(2, 4, 6, 8, 10);
        $r3 = $sum($r1, $r2);
        $var_dump($r1, $r2, $r3);
    }

}