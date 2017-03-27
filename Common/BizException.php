<?php

/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 11:53
 */
class BizException extends Exception
{
    private $data;

    public function __construct($err_info, $data = array())
    {
        if(is_scalar($err_info)){
            $err_info = array('msg' => $err_info, 'code' => ErrorInfo::$err_custom_code);
        }

        $this->data = $data;

        parent::__construct($err_info['msg'], $err_info['code']);
    }

    public function getData(){
        return $this->data;
    }
}