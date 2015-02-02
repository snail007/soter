<?php

/**
 * @property Soter_Config $soterConfig
 */
class Soter {

	private static $soterConfig;

	public static function classAutoloader($className) {
		$config = self::$soterConfig;
		$className = str_replace('_', '/', $className);
		foreach (self::$soterConfig->getPackages() as $path) {
			if (file_exists($filePath = $path . $config->getClassesName() . '/' . $className . '.php')) {
				Sr::includeOnce($filePath);
			}
		}
	}

	/**
	 * 
	 * @return \Soter_Config
	 */
	public static function initialize() {
		self::$soterConfig = new Soter_Config();
		//注册错误处理
		Soter_Logger_Writer_Dispatcher::initialize();
		//注册类自动加载
		if (function_exists('__autoload')) {
			spl_autoload_register('__autoload');
		}
		spl_autoload_register(array('Soter', 'classAutoloader'));
		//清理魔法转义
		if (get_magic_quotes_gpc()) {
			$stripList = array('_GET', '_POST', '_COOKIE');
			foreach ($stripList as $val) {
				global $$val;
				$$val = Sr::stripSlashes($$val);
			}
		}
		return self::$soterConfig;
	}

	/**
	 * 
	 * @return Soter_Config
	 */
	public static function &getConfig() {
		return self::$soterConfig;
	}

	public static function run() {
		$config = self::getConfig();
		foreach (array_reverse($config->getRouters()) as $router) {
			$route = $router->find($config->getRequest());
			if ($route->found()) {
				$route = $router->route();
				$class = $route->getController();
				if (!class_exists($class)) {
					throw new Soter_Exception_404('Controller [ ' . $class . ' ] not found');
				}
				$controllerObject = new $class();
				$method = $route->getMethod();
				if (!method_exists($controllerObject, $method)) {
					throw new Soter_Exception_404('Method [ ' . $class . '->' . $method . '() ] not found');
				}
				$response = call_user_func_array(array($controllerObject, $method), $route->getArgs());
				if ($response instanceof Soter_Response) {
					$response->output();
				} else {
					echo $response;
				}
				exit();
			} else {
				$exception = new Soter_Exception_404($route->getController());
				throw $exception;
			}
		}
	}

}

class Sr {

	private static $includeFiles = array();

	static function arrayGet($array, $key, $default = null) {
		return isset($array[$key]) ? $array[$key] : $default;
	}

	static function dump() {
		echo!self::isCli() ? '<pre style="line-height:1.5em;font-size:14px;">' : "\n";
		@ob_start();
		call_user_func_array('var_dump', func_get_args());
		$html = @ob_get_clean();
		echo htmlspecialchars($html);
		echo!self::isCli() ? "</pre>" : "\n";
	}

	public static function includeOnce($filePath) {
		$key = self::realPath($filePath);
		if (!isset(self::$includeFiles[$key])) {
			include $filePath;
			self::$includeFiles[$key] = 1;
		}
	}

	static function realPath($path) {
		//是linux系统么？
		$unipath = PATH_SEPARATOR == ':';
		//检测一下是否是相对路径，windows下面没有:,linux下面没有/开头
		//如果是相对路径就加上当前工作目录前缀
		if (strpos($path, ':') === false && strlen($path) && $path{0} != '/') {
			$path = realpath('.') . DIRECTORY_SEPARATOR . $path;
		}
		// resolve path parts (single dot, double dot and double delimiters)
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
		$parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
		$absolutes = array();
		foreach ($parts as $part) {
			if ('.' == $part)
				continue;
			if ('..' == $part) {
				array_pop($absolutes);
			} else {
				$absolutes[] = $part;
			}
		}
		//如果是linux这里会导致linux开头的/丢失
		$path = implode(DIRECTORY_SEPARATOR, $absolutes);
		//如果是linux，修复系统前缀
		$path = $unipath ? (strlen($path) && $path{0} != '/' ? '/' . $path : $path) : $path;
		//最后统一分隔符为/，windows兼容/
		$path = str_replace(array('/', '\\'), '/', $path);
		return $path;
	}

	static function isCli() {
		return PHP_SAPI == 'cli';
	}

	static function stripSlashes($var) {
		if (!get_magic_quotes_gpc()) {
			return $var;
		}
		if (is_array($var)) {
			foreach ($var as $key => $val) {
				if (is_array($val)) {
					$var[$key] = self::stripSlashes($val);
				} else {
					$var[$key] = stripslashes($val);
				}
			}
		} elseif (is_string($var)) {
			$var = stripslashes($var);
		}
		return $var;
	}

}
