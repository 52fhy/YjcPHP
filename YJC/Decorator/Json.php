<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/6/24 024
 * Time: 16:27
 */

namespace YJC\Decorator;


use YJC\IResponse;

class Json extends Decorator
{
    public function output($data)
    {
        header('Content-type: application/json');

        $response =  str_replace('null', '""', json_encode($data));

        //支持jsonp
        if (isset($_GET['callback'])) {
            $response = $_GET['callback'] . '(' . $response . ')';
        }

        echo $response;

        return $response;
    }

}