<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/9/27 027
 * Time: 23:18
 */

namespace YJC\Payment\Alipay\Utils;


class Config
{
    public static function getConfig(){

        $config = require_once dirname(__DIR__).'/common/alipay.config.php';

        return $config;
    }

}