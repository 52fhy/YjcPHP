<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2018-3-24 
 * Time: 14:46:12
 */

namespace YJC\Decorator;


use YJC\IResponse;

class Msgpack extends Decorator
{
    public function output($data)
    {
        header('Content-type: application/json');
        $response =  msgpack_pack($data);
        echo $response;
    }

}