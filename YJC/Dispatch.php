<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 12:08
 */

namespace YJC;


class Dispatch
{
    protected static $ins;

    const URL_TYPE_NORMAL = 0;
    const URL_TYPE_PATHINFO = 1;
    const URL_TYPE_CLI = 2;

    public static function getInstance(){
        if(!self::$ins){
            self::$ins = new self;
        }

        return self::$ins;
    }

    /**
     * 路由分配
     */
    public static function run(){

        $post_data = file_get_contents("php://input");

        $begin_time = microtime(true);

        //decorator
        $decorator = self::getDecorator();

        try{

            list($controller, $action) = self::parseRoute();

            $controller = ucwords($controller);
            define('CONTROLLER_NAME', $controller);
            define('ACTION_NAME', $action);

            $class = APP_NAME .'\\Controller\\' . $controller;

            $obj = new $class();

            $decorator->beforeRequest($obj);

            //统一接收json数据
            if (method_exists($obj, 'setPostData')) {
                $obj->setPostData($post_data);
            }

            $return = $obj->run($action);

            $succ_info = \ErrorInfo::$succ;
            $result['code'] = $succ_info['code'];
            $result['msg'] = $succ_info['msg'];
            $result['data'] = $return;

        }catch(\BizException $e){
            $result['code'] = $e->getCode();
            $result['msg'] = $e->getMessage();
            $result['data'] = $e->getData();

            $log_msg = array();
            $log_msg['code'] = $e->getCode();
            $log_msg['TraceAsString'] = var_export($e, true);
            Logger::writeExceptionLog($log_msg);
        }catch(\Exception $e){
            $result['code'] = $e->getCode();
            $result['msg'] = $e->getMessage();

            $log_msg = array();
            $log_msg['code'] = $e->getCode();
            $log_msg['TraceAsString'] = var_export($e, true);
            Logger::writeExceptionLog($log_msg);
        }

        $response = $decorator->afterRequest($result);

        //访问日志记录
        $end_time = microtime(true);
        $run_time = round(($end_time - $begin_time) * 1000, 2);
        $msg = $_SERVER['REQUEST_URI']  . "---" . $post_data . "---" . $response . "---" . $run_time;
        Logger::writeBizAccessLog($msg);

        exit;
    }

    /**
     * 路由解析
     * @return array
     */
    private static function parseRoute(){
        //路由类型
        $url_type = App::getConfig()['config']['url_type'];
        switch($url_type){
            case self::URL_TYPE_NORMAL:
                if(isset($_GET['c']) && isset($_GET['a'])){
                    $controller = $_GET['c'];
                    $action = $_GET['a'];
                }elseif(isset($_GET['c'])){
                    $controller = $_GET['c'];
                    $action = 'index';
                }else{
                    $controller = 'index';
                    $action = 'index';
                }
                break;

            case self::URL_TYPE_PATHINFO:

                $uri = explode('?', $_SERVER['REQUEST_URI']); //分出路由部分和参数部分
                $uri_part = trim($uri[0], '/'); //取出路由部分

                $uri_arr = explode('/', $uri_part);

                if(strpos($uri_arr[0], '.') !== false){
                    array_shift($uri_arr);
                }

                if(!empty($uri_arr[0]) && !empty($uri_arr[1])){
                    $controller = $uri_arr[0];
                    $action = $uri_arr[1];
                }elseif(!empty($uri_arr[0])){
                    $controller = $uri_arr[0];
                    $action = 'index';
                }else{
                    $controller = 'index';
                    $action = 'index';
                }
                break;

            case self::URL_TYPE_CLI:
                $uri_arr = $_SERVER['argv'];
                if (count($uri_arr) <= 1) {
                    exit('Bad request.');
                }

                $controller = $uri_arr[2];
                $action = $uri_arr[3];

                break;
        }

        return array($controller, $action);
    }

    private static function getDecorator(){
        $return_type = strtolower(App::getConfig()['config']['return_type']);
        switch($return_type){
            case 'json':
                $decorator = 'YJC\\Decorator\\Json';break;
            case 'xml':
                $decorator = 'YJC\\Decorator\\Xml';break;
            case 'html':
                $decorator = 'YJC\\Decorator\\Template';break;
            default:
                $decorator = 'YJC\\Decorator\\'.ucfirst($return_type);break;
        }
        return new $decorator();
    }
}