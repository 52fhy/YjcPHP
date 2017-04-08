<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/3/27 027
 * Time: 21:19
 */

namespace App\Controller;

class HproseServer extends HproseBase
{
    public function test1($name){
        return "Hello $name!";
    }
    public function test2(){
        return 'test2 333';
    }

    public function sum(){
        $args = func_get_args();
        $sum = 0;
        foreach ($args as $vo){
            $sum += $vo;
        }
        return $sum;
    }
}