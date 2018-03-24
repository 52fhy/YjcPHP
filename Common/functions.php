<?php
/**
 * functions.php 基础函数库
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 14:14
 */

/**
 * 获取用户ip地址
 * @return string
 */
function getIp()
{
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    if (!ip2long($ip)) {
        $ip = '';
    }
    return $ip;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else
        return $output;
}

/**
 * 便捷的输出当前时间的字符串
 * @return string
 */
function now()
{
    return date('YmdHis');
}

/**
 * 将时间戳转化为友好的时间表达，如输出今天、明天
 * @author: YJC
 * @param int $timestamp 时间戳
 * @return string
 */
function time2str($timestamp)
{
    $today_zero = strtotime(date('Y-m-d', time()));
    $diff = ($timestamp - $today_zero) / (3600 * 24);
    $diff = intval($diff);
    switch ($diff) {
        case 0 :
            $str = '今天';
            break;
        case 1 :
            $str = '明天';
            break;
        case 2 :
            $str = '后天';
            break;
        default:
            $str = date('m月d日', $timestamp);
            break;
    }
    return $str;
}

function showJsonInfo($data)
{
    $data = array(
        'code' => 0,
        'msg' => '成功',
        'data' => $data
    );

    echo json_encode($data);
    // exit;
}

/**
 * des_ecb模式加密
 * @date: 2015-2-26 下午3:38:03
 * @author: YJC
 */
function des_encode($str, $key)
{
    $des = new Crypt_DES(CRYPT_DES_MODE_ECB);
    $des->setKey($key);
    return $des->encrypt($str);
}

/**
 * des_ecb模式解密
 * @date: 2015-2-26 下午3:38:03
 * @author: YJC
 */
function des_decode($str, $key)
{
    $des = new Crypt_DES(CRYPT_DES_MODE_ECB);
    $des->setKey($key);
    return $des->decrypt($str);
}

function arr2xml($arr,$node=null) {
    if($node === null) {
        $simxml = new simpleXMLElement('<?xml version="1.0" encoding="utf-8"?><root></root>');
    } else {
        $simxml = $node;
    }
    // simpleXMLElement对象如何增加子节点?

    foreach($arr as $k=>$v) {
        if(is_array($v)) {
            //$simxml->addChild($k);
            arr2xml($v,$simxml->addChild($k));
        } else if(is_numeric($k)) { //标签不能以数字开头，和变量类似
            $simxml->addChild('item' . $k,$v);
        } else {
            $simxml->addChild($k,$v);
        }
    }

    return $simxml->saveXML();
}

function model($name, $type = 'master'){
    return \YJC\Factory::getModel($name, $type);
}

function M($name, $type = 'master'){
    return call_user_func('model', $name. 'Model', $type);
}

function get_app(){
    return \YJC\App::getInstance();
}

/**
 * 配置获取
 * @param $key
 * @return mixed
 */
function config($key){
    $config = \YJC\App::getConfig();
    if(strpos($key, '.') !== false){
        $keys = explode('.', $key);
        return $config[$keys[0]][$keys[1]];
    }else{
        return $config[$key];
    }
}