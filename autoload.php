<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 19:51
 */

require_once BASE_PATH. '/YJC/Loader.php';
require_once BASE_PATH. '/Common/functions.php';
require_once BASE_PATH. '/Common/Enum.php';
require_once BASE_PATH. '/Common/ErrorInfo.php';
require_once BASE_PATH. '/Common/BizException.php';

//支持composer
require_once BASE_PATH. '/vendor/autoload.php';

require_once BASE_PATH. '/third_party/eloquent.php';
require_once BASE_PATH. '/third_party/swoole.php';

spl_autoload_register('\\YJC\\Loader::autoload');

//init Logger
\YJC\Loader::initializeLogger();
