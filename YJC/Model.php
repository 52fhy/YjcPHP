<?php
namespace YJC;

use \BizException;

class Model{

	protected $table = '';

	protected static $medoo;

	public function __construct($type = ''){
		self::getInstance($type);
	}

	public function __call($name, $args){
		if(method_exists(self::$medoo, $name)){
			switch (count($args)) {
				case 1:
					return self::$medoo->$name($this->table, $args[0]);
					break;
				case 2:
					return self::$medoo->$name($this->table, $args[0], $args[1]);
					break;
				case 3:
					return self::$medoo->$name($this->table, $args[0], $args[1], $args[2]);	
				case 4:
				return self::$medoo->$name($this->table, $args[0], $args[1], $args[2], $args[3]);	
			}
		}

		throw new BizException("method is not exists");

	}

	public static function getInstance($type = 'master'){
		$config = App::getConfig()['db'][$type];
		if(!self::$medoo){
			self::$medoo = new Medoo($config);
		}

		return self::$medoo;
	}

	public function getPDO(){
		return self::$medoo->pdo;
	}
}