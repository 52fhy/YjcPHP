<?php

namespace App\Controller;
use YJC\App;

/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 11:59
 */
class Base extends App
{
    protected $post_data;
    protected $param_data;

    /**
     * 统一接收数据
     * @param $post_data
     */
    public function setPostData($post_data){
        $this->post_data = $post_data;
        $this->param_data = json_decode(trim($post_data), true);
    }

}