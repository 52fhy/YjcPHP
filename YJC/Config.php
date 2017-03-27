<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 11:37
 */

namespace YJC;


class Config implements \ArrayAccess
{
    /**
     * @var string $dir 配置文件目录
     */
    private $dir;
    private $configs;

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function offsetExists($key)
    {
        return isset($this->configs[$key]);
    }

    public function offsetGet($key)
    {
        $file = $this->dir . '/' . $key .'.php';
        if(!isset($this->configs[$key])){
            $this->configs[$key] = include $file;
        }

        return $this->configs[$key];
    }

    public function offsetSet($key, $value)
    {
        return false;
    }

    public function offsetUnset($key)
    {
        unset($this->configs[$key]);
    }
}