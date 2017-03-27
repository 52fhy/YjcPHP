<?php

abstract class ErrorInfo
{
    /**
     * 状态码
     */
    static public $succ = array('code' => 0, 'msg' => '成功');
    static public $err_bad_request = array('code' => 9001, 'msg' => '非法请求');
    static public $err_inner = array('code' => 9002, 'msg' => '请稍后再试');
    static public $err_permissions = array('code' => 9003, 'msg' => '您无权进行该操作');
    static public $err_special = array('code' => 9004, 'msg' => '未知错误');
    static public $err_return_url = array('code' => 9005, 'msg' => '请跳转');
    static public $err_miss_param = array('code' => 9006, 'msg' => '缺少参数');

    /**
     * 自定义错误
    */
    static public $err_custom_code = 9999;
}