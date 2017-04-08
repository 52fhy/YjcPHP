<?php

namespace YJC\Decorator;

/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 14:41
 */
class Hprose
{
    protected $controller;

    public function beforeRequest($obj){
        $this->controller = $obj;
    }

    public function afterRequest($return_value){

    }

}