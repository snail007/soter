<?php

/**
 * @property Soter_Config $soterConfig
 */
class Soter {

	private static $soterConfig;

	/**
	 * 包类库自动加载器
	 * @param type $className
	 */
	public static function classAutoloader($className) {
		$config = self::$soterConfig;
		$className = str_replace('_', '/', $className);
		foreach (self::$soterConfig->getPackages() as $path) {
			if (file_exists($filePath = $path . $config->getClassesDirName() . '/' . $className . '.php')) {
				Sr::includeOnce($filePath);
				break;
			}
		}
	}

	/**
	 * 初始化框架配置
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
	 * 获取运行配置
	 * @return Soter_Config
	 */
	public static function &getConfig() {
		return self::$soterConfig;
	}

	/**
	 * 运行调度
	 */
	public static function run() {
		if (Sr::isCli()) {
			self::runCli();
		} elseif (defined('SOTER_RUN_MODE_PLUGIN') && SOTER_RUN_MODE_PLUGIN) {
			self::runPlugin();
		} else {
			self::runWeb();
		}
	}

	/**
	 * web模式运行
	 * @throws Soter_Exception_404
	 */
	private static function runWeb() {
		$config = self::getConfig();
		$class = '';
		$method = '';
		foreach ($config->getRouters() as $router) {
			$route = $router->find($config->getRequest());
			if ($route->found()) {
				$route = $router->route();
				$config->setRoute($route);
				$class = $route->getController();
				$method = $route->getMethod();
				break;
			}
		}
		if (empty($class)) {
			$class = $config->getControllerDirName() . '_' . $config->getDefaultController();
		}
		if (empty($method)) {
			$method = $config->getMethodPrefix() . $config->getDefaultMethod();
		}
		$controllerObject = new $class();
		if (!($controllerObject instanceof Soter_Controller)) {
			throw new Soter_Exception_500('[ ' . $class . ' ] not a valid Soter_Controller');
		}
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
	}

	/**
	 * 命令行模式运行
	 */
	private static function runCli() {
		$task = Sr::getOpt('task');
		$hmvcModuleName = Sr::getOpt('hmvc');
		if (empty($task)) {
			exit('require a task name,please use --task=<taskname>' . "\n");
		}
		if (!empty($hmvcModuleName)) {
			self::checkHmvc($hmvcModuleName);
		}
		$taskName = Sr::config()->getTaskDirName() . '_' . $task;
		if (!class_exists($taskName)) {
			throw new Soter_Exception_404('class [ ' . $taskName . ' ] not found');
		}
		$taskObject = new $taskName();
		if (!($taskObject instanceof Soter_Task)) {
			throw new Soter_Exception_500('[ ' . $taskName . ' ] not a valid Soter_Task');
		}
		$args = Sr::getOpt();
		$args = empty($args) ? array() : $args;
		$taskObject->execute(new Soter_CliArgs($args));
	}

	/**
	 * 插件模式运行
	 */
	private static function runPlugin() {
		//插件模式
	}

	/**
	 * 插件模式下的超级工厂类
	 * @param type $className      可以是控制器类名，模型类名，类库类名
	 * @param type $hmvcModuleName hmvc模块名称，是配置里面的数组的键名
	 * @return \className
	 * @throws Soter_Exception_404
	 */
	public static function plugin($className, $hmvcModuleName = null) {
		if (!defined('SOTER_RUN_MODE_PLUGIN') || !SOTER_RUN_MODE_PLUGIN) {
			throw new Soter_Exception_500('Sr::plugin() only in PLUGIN mode');
		}
		//hmvc检测
		self::checkHmvc($hmvcModuleName);
		return new $className();
	}

	/**
	 * 检测并加载hmvc模块
	 * @staticvar array $loadedModules
	 * @param type $hmvcModuleName
	 * @throws Soter_Exception_404
	 */
	private static function checkHmvc($hmvcModuleName) {
		//hmvc检测
		if (!empty($hmvcModuleName)) {
			$config = Soter::getConfig();
			$hmvcModules = $config->getHmvcModules();
			if (empty($hmvcModules[$hmvcModuleName])) {
				throw new Soter_Exception_404('Hmvc Module [ ' . $hmvcModuleName . ' ] not found, please check your config.');
			}
			//避免重复加载，提高性能
			static $loadedModules = array();
			$hmvcModuleDirName = $hmvcModules[$hmvcModuleName];
			if (!isset($loadedModules[$hmvcModuleName])) {
				$loadedModules[$hmvcModuleName] = 1;
				//找到hmvc模块,去除hmvc模块名称，得到真正的路径
				$hmvcModulePath = $config->getApplicationDir() . $config->getHmvcDirName() . '/' . $hmvcModuleDirName . '/';
				//设置hmvc子项目目录为主目录，同时注册hmvc子项目目录到主包容器，以保证高优先级
				$config->setApplicationDir($hmvcModulePath)->addMasterPackage($hmvcModulePath);
			}
		}
	}

}

class Sr {

	const ENV_TESTING = 1; //测试环境
	const ENV_PRODUCTION = 2; //产品环境
	const ENV_DEVELOPMENT = 3; //开发环境

	private static $includeFiles = array();

	static function arrayGet($array, $key, $default = null) {
		return isset($array[$key]) ? $array[$key] : $default;
	}

	static function dump() {
		echo!self::isCli() ? '<pre style="line-height:1.5em;font-size:14px;">' : "\n";
		@ob_start();
		call_user_func_array('var_dump', func_get_args());
		$html = @ob_get_clean();
		echo!self::isCli() ? htmlspecialchars($html) : $html;
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

	static function business($businessName) {
		$name = Sr::config()->getBusinessDirName() . '_' . $businessName;
		$object = self::factory($name);
		if (!($object instanceof Soter_Business)) {
			throw new Soter_Exception_500('[ ' . $name . ' ] not a valid Soter_Bussiness');
		}
		return $object;
	}

	static function dao($daoName) {
		$name = Sr::config()->getDaoDirName() . '_' . $daoName;
		$object = self::factory($name);
		if (!($object instanceof Soter_Dao)) {
			throw new Soter_Exception_500('[ ' . $name . ' ] not a valid Soter_Dao');
		}
		return $object;
	}

	/**
	 * web模式和命令行模式下的超级工厂方法
	 * @param type $className
	 * @return \className
	 * @throws Soter_Exception_404
	 */
	static function factory($className) {
		if (defined('SOTER_RUN_MODE_PLUGIN') && SOTER_RUN_MODE_PLUGIN) {
			throw new Soter_Exception_500('Sr::factory() only in web or cli mode');
		}
		if (!class_exists($className)) {
			throw new Soter_Exception_404("class [ $className ] not found");
		}
		return new $className();
	}

	static function &config() {
		return Soter::getConfig();
	}

	/**
	 * 插件模式下的超级工厂类
	 * @param type $className      可以是控制器类名，模型类名，类库类名
	 * @param type $hmvcModuleName hmvc模块名称，是配置里面的数组的键名
	 * @return \className
	 * @throws Soter_Exception_404
	 */
	public static function plugin($className, $hmvcModuleName = null) {
		return Soter::plugin($className, $hmvcModuleName);
	}

	static function loadConfig($configName) {
		$_info = explode('.', $configName);
		$configFileName = current($_info);
		static $loadedConfig = array();
		$cfg = null;
		if (isset($loadedConfig[$configFileName])) {
			$cfg = $loadedConfig[$configFileName];
		} else {
			$config = Soter::getConfig();
			foreach ($config->getPackages() as $packagePath) {
				$filePath = $packagePath . $config->getConfigDirName() . '/' . $config->getConfigCurrentDirName() . '/' . $configFileName . '.php';
				$fileDefaultPath = $packagePath . $config->getConfigDirName() . '/default/' . $configFileName . '.php';
				$contents = '';
				if (file_exists($filePath)) {
					$contents = file_get_contents($filePath);
				} elseif (file_exists($fileDefaultPath)) {
					$contents = file_get_contents($fileDefaultPath);
				}
				if ($contents) {
					$cfg = eval('?>' . $contents);
					$loadedConfig[$configFileName] = $cfg;
					break;
				}
			}
		}
		if ($cfg && count($_info) > 1) {
			array_shift($_info);
			$keyStrArray = '';
			foreach ($_info as $k) {
				$keyStrArray.= "['" . $k . "']";
			}
			return eval('return isset($cfg' . $keyStrArray . ')?$cfg' . $keyStrArray . ':null;');
		} else {
			return $cfg;
		}
	}

	/**
	 * 解析命令行参数 $GLOBALS['argv'] 到一个数组
	 *
	 * 参数形式支持:
	 * -e
	 * -e <value>
	 * --long-param
	 * --long-param=<value>
	 * --long-param <value>
	 * <value>
	 *
	 */
	static function getOpt($key = null) {
		if (!self::isCli()) {
			return null;
		}
		$noopt = array();
		static $result = array();
		static $parsed = false;
		if (!$parsed) {
			$parsed = true;
			$params = self::arrayGet($GLOBALS, 'argv', array());
			reset($params);
			while (list($tmp, $p) = each($params)) {
				if ($p{0} == '-') {
					$pname = substr($p, 1);
					$value = true;
					if ($pname{0} == '-') {
						$pname = substr($pname, 1);
						if (strpos($p, '=') !== false) {
							list($pname, $value) = explode('=', substr($p, 2), 2);
						}
					}
					$nextparm = current($params);
					if (!in_array($pname, $noopt) && $value === true && $nextparm !== false && $nextparm{0} != '-') {
						list($tmp, $value) = each($params);
					}
					$result[$pname] = $value;
				} else {
					$result[] = $p;
				}
			}
		}
		return empty($key) ? $result : (isset($result[$key]) ? $result[$key] : null);
	}

}
