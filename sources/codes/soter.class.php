<?php

class Soter {

	private static $soterConfig, $instance;

	/**
	 * 
	 * @return \Soter
	 */
	public static function initialize() {
		self::$instance = new Soter();
		self::$soterConfig = new Soter_Config();
		return self::$instance;
	}

	/**
	 * 
	 * @return Soter_Config
	 */
	public static function &getConfig() {
		return self::$soterConfig;
	}

	public static function run() {
		foreach (array_reverse(self::getConfig()->getRouters()) as $router) {
			if ($router->isOkay()) {
				$route = $router->route();
				$class = $route->getController();
				$method = $route->setMethod();
				$response = call_user_func_array(array($class, $method));
				if($response instanceof Soter_Response){
					$response->output();
				}else{
					exit($response);
				}
			} else {
				$exception = new Soter_Exception_404();
				$exception->setErrorMessage('not found');
				$exception->setErrorFile();
				throw $exception;
			}
		}
	}

}

class S {

	static function arrayGet($array, $key, $default = null) {
		return isset($array[$key]) ? $array[$key] : $default;
	}

}
