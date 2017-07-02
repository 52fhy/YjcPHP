<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/7/1 001
 * Time: 10:01
 */


$closure = function ($name) {
    return 'Hello ' . $name;
};
echo $closure('nesfo');//
var_dump(method_exists($closure, '__invoke'));//true