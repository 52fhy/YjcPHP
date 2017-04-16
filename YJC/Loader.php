<?php

namespace YJC;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

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

    public static function initializeLogger()
    {
        if (Log::hasLogger()) {
            return;
        }

        $config = App::getConfig()['config'];

        $logger = new Logger('Yjc');

        if (defined('PHPUNIT_RUNNING')) {
            $logger->pushHandler(new NullHandler());
        } elseif ($config['log']['handler'] instanceof HandlerInterface) {
            $logger->pushHandler($config['log']['handler']);
        } elseif ($logFile = $config['log']['file']) {
            $logger->pushHandler(new StreamHandler(
                    $logFile,
                    $config['log']['level'],
                    true,
                    null)
            );
        }

        Log::setLogger($logger);
    }
}