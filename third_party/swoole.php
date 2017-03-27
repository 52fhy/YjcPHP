<?php

/**
 * 自动加载
 */
spl_autoload_register(function ($class) {
    if (false !== strpos($class, 'Swoole\\')) {
        require_once __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 0)) . '.php';
    }
});