<?php

namespace YJC\Decorator;

/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 14:41
 */
class Json
{
    protected $controller;

    public function beforeRequest($obj){
        $this->controller = $obj;
    }

    public function afterRequest($return_value){

        $response =  str_replace('null', '""', json_encode($return_value));

        //支持jsonp
        if (isset($_GET['callback'])) {
            $response = $_GET['callback'] . '(' . $response . ')';
        }

        echo $response;

        return $response;
    }

}