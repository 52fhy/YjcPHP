<?php

class HttpServer
{
	public static $instance;

	public $http;
	public static $get;
	public static $post;
	public static $header;
	public static $server;
	private $application;

	public function __construct() {
		$http = new swoole_http_server("0.0.0.0", 9051);

		$http->set(
			array(
				'worker_num' => 10,
				'daemonize' => true,
	            'max_request' => 10000,
				'dispatch_mode' => 1,
				'log_file' => '../logs/swoole.log',
			)
        );
        
        $http->on('Start', function() {
            swoole_set_process_name("yjcphp_server");
            echo "Server Start\n";
        });

		$http->on('WorkerStart' , array( $this , 'onWorkerStart'));

		$http->on('request', function ($request, $response) {
			if( isset($request->server) ) {
				foreach ($request->server as $key => $value) {
                    unset($_SERVER[ strtoupper($key) ]);
					$_SERVER[ strtoupper($key) ] = $value;
				}
			}
			if( isset($request->header) ) {
				foreach ($request->header as $key => $value) {
                    unset($_SERVER[ strtoupper($key) ]);
					$_SERVER[ strtoupper($key) ] = $value;
				}
			}
            unset($_GET);
			if( isset($request->get) ) {
				foreach ($request->get as $key => $value) {
					$_GET[ $key ] = $value;
				}
			}
            unset($_POST);
			if( isset($request->post) ) {
				foreach ($request->post as $key => $value) {
					$_POST[ $key ] = $value;
				}
			}
            unset($_COOKIE);
			if( isset($request->cookie) ) {
				foreach ($request->cookie as $key => $value) {
					$_COOKIE[ $key ] = $value;
				}
			}
            unset($_FILES);
			if( isset($request->files) ) {
				foreach ($request->files as $key => $value) {
					$_FILES[ $key ] = $value;
				}
			}

			ob_start();
            (new \YJC\Dispatch())->runMvc();
		    $result = ob_get_contents();
			ob_end_clean();
			  
		  	$response->end($result);
		});

		$http->start();
	}

	public function onWorkerStart() {
        
        ini_set('display_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);

		define('BASE_PATH', dirname(__DIR__));
        define('APP_NAME', 'App'); //应用名称，与应用路径一致
        define('APP_PATH', BASE_PATH .'/' . APP_NAME );
        define('Core_PATH', BASE_PATH .'/YJC');
        define('STATIC_PATH', BASE_PATH .'/Common/static');

        require_once BASE_PATH. '/autoload.php';
	}

	public static function getInstance() {
		if (!self::$instance) {
            self::$instance = new HttpServer;
        }
        return self::$instance;
	}
}

HttpServer::getInstance();
