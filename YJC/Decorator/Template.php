<?php

namespace YJC\Decorator;

/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 14:41
 */
class Template
{
    protected $controller;

    public function beforeRequest($obj){
        $this->controller = $obj;
    }

    public function afterRequest($return_value){

        if(!is_object($this->controller)){
            exit($return_value['msg']);
        }

        $data = $return_value['data'];
        if(empty($data)){
            return;
        }

        if(is_array($data)){
            foreach($data as $k=> $v){
                $this->controller->assign($k, $v);
            }
        }else{
            $this->controller->assign('data', $data);
        }

        $this->controller->display();
    }

}