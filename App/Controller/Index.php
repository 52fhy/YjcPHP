<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 12:23
 */

namespace App\Controller;


use App\Model\TestModel;
use App\Model\UserModel;
use Swoole\Client\WebSocket;

use YJC\Factory;
use Yjc\Sms\Jsms;
use Yjc\Sms\Ucpaas;

class Index extends Base
{
    public function __construct(){
        $this->before_filter("checkLogin", array('*', 'exclude' => array('setImage')));
    }

    public function checkLogin(){

    }

    public function index(){

        //dump($this->param_data);

        //$user_model = Factory::getModel('UserModel');
//        $user_model = M('User');
//        $data =  $user_model->select('*', array('age[=] ' => 20));

        $data = [
            'name' => 'yjcphp',
        ];

        // $data = TestModel::find(1);

//        $this->assign('list', $data);
//        $this->display();
        return $data;
    }

    public function test_model(){
        return TestModel::find(1);
    }

    public function verify(){
        $Verify = new \YJC\Think\Verify();
        return $Verify->entry();
    }
}