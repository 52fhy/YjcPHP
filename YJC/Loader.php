<?php

namespace YJC;

/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 11:12
 */
class Loader
{
    public static function autoload($class){
        if (false !== strpos($class, '\\')) {
            $file = str_replace(BASE_PATH . '\\', '/', trim($class, '\\')) . '.php';
            if(file_exists($file)){
                require_once $file;
            }else{
                throw new \BizException('Cannot find class file:'. $file);
            }
        }
    }
}