<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 11:18
 */

namespace YJC;


class Register
{
    protected static $objects;

    /**
     * 注册对象
     * @param $key
     * @param $obj
     */
    public static function set($key, $obj){
        self::$objects[$key] = $obj;
    }

    /**
     * 获取对象
     * @param $key
     * @return bool
     */
    public static function get($key){
        if(!isset(self::$objects[$key])){
           return false;
        }

        return self::$objects[$key];
    }

    public static function _unset($key){
        unset(self::$objects[$key]);
    }
}