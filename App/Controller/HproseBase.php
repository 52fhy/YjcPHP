<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/4/8 008
 * Time: 9:48
 */

namespace App\Controller;

use Hprose\Http\Server;

class HproseBase extends Base
{
    protected $allowMethodList  =   '';
    protected $crossDomain      =   false;
    protected $P3P              =   false;
    protected $get              =   false;
    protected $debug            =   false;

    /**
     * 架构函数
     * @access public
     */
    public function __construct() {

        //实例化HproseHttpServer
        $server     =   new Server();
        if($this->allowMethodList){
            $methods    =   $this->allowMethodList;
        }else{
            $methods    =   get_class_methods($this);
            $methods    =   array_diff($methods,array('__construct','__call','setPostData','getConfig','getInstance','assign','display','before_filter','after_filter','run'));
        }
        $server->addMethods($methods,$this);
        if($this->debug ) {
            $server->setDebugEnabled(true);
        }
        // Hprose设置
        $server->setCrossDomainEnabled($this->crossDomain);
        $server->setP3PEnabled($this->P3P);
        $server->setGetEnabled($this->get);
        // 启动server
        $server->start();
    }

    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method,$args){}

}