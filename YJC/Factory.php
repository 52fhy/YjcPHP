<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 11:25
 */

namespace YJC;


class Factory
{

    /**
     * 获取模型
     * @param $name
     */
    public static function getModel($name, $type = 'master'){
        $key = 'app_model_'.$name;
        $model = Register::get($key);
        if(!$model){
            $class = APP_NAME . '\\Model\\'.ucwords($name);
            $model = new $class($type);
            Register::set($key, $model);
        }

        return $model;
    }
}