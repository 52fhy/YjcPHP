<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2018-3-24 
 * Time: 14:36:37
 */

namespace YJC\Decorator;


use YJC\IResponse;

class Pure extends Decorator
{
    public function output($data)
    {
        // nothing to do
    }

}