<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 11:11
 */

define('BASE_PATH', __DIR__);
define('APP_NAME', 'App'); //应用名称，与应用路径一致
define('APP_PATH', BASE_PATH .'/' . APP_NAME );
define('Core_PATH', BASE_PATH .'/YJC');
define('STATIC_PATH', BASE_PATH .'/Common/static');

require_once BASE_PATH. '/autoload.php';

\YJC\Dispatch::getInstance()->run();

