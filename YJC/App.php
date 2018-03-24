<?php

namespace YJC;

/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 11:59
 */
class App extends Application implements IResponse
{
    protected $view_data;
    protected static $ins;
    protected static $configs;

    /**
     * @return Config
     */
    public static function getConfig(){
        if(!self::$configs){
            self::$configs = new Config(APP_PATH .'/Config');
        }

        return self::$configs;
    }

    public static function getInstance(){
        if(!self::$ins){
            self::$ins = new self;
        }

        return self::$ins;
    }

    public function assign($key, $view_data){
        $this->view_data[$key] = $view_data;
    }

    /**
     * 模板显示
     * @param string $file
     */
    public function display($file = ''){
        $view_path = APP_PATH . '/View/';
        if(empty($file)){
            $file = $view_path. CONTROLLER_NAME . '/' . ACTION_NAME . '.php';
        }elseif($file && strpos($file, '/') === false){
            $file = $view_path. CONTROLLER_NAME . '/' . $file . '.php';
        }else{
            $file = $view_path . '/' . $file. '.php';
        }

        if(!file_exists($file)){
            exit("Cannot find template file: " . strstr($file, '/'));
        }

        extract($this->view_data);
        include $file;
    }

    /**
     * @param $data
     */
    public function output($data)
    {
        $return_type = strtolower( (isset($_GET['output']) && $_GET['output']) ? $_GET['output'] : App::getConfig()['config']['output_type']);
        $decorator = 'YJC\\Decorator\\'.ucfirst($return_type);
        (new $decorator($this))->output($data);
    }
}