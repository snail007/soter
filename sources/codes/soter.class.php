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
		if (Sr::isPluginMode()) {
			self::runPlugin();
		} elseif (Sr::isCli()) {
			self::runCli();
		} else {
			$canRunWeb = !Sr::config()->getIsMaintainMode();
			if (!$canRunWeb) {
				foreach (Sr::config()->getMaintainIpWhitelist() as $ip) {
					$info = explode('/', $ip);
					$netmask = empty($info[1]) ? '32' : $info[1];
					if (Sr::ipInfo(Sr::clientIp() . '/' . $netmask, 'netaddress') == Sr::ipInfo($info[0] . '/' . $netmask, 'netaddress')) {
						$canRunWeb = true;
						break;
					}
				}
			}
			if ($canRunWeb) {
				self::runWeb();
			} else {
				$handle = Sr::config()->getMaintainModeHandle();
				if (is_object($handle)) {
					$handle->handle();
				}
			}
		}
	}

	/**
	 * web模式运行
	 * @throws Soter_Exception_404
	 */
	private static function runWeb() {
		$config = self::getConfig();
		//session初始化
		$sessionConfig = $config->getSessionConfig();
		@ini_set('session.auto_start', 0);
		@ini_set('session.gc_probability', 1);
		@ini_set('session.gc_divisor', 100);
		@ini_set('session.gc_maxlifetime', $sessionConfig['lifetime']);
		@ini_set('session.referer_check', '');
		@ini_set('session.entropy_file', '/dev/urandom');
		@ini_set('session.entropy_length', 16);
		@ini_set('session.use_cookies', 1);
		@ini_set('session.use_only_cookies', 1);
		@ini_set('session.use_trans_sid', 0);
		@ini_set('session.hash_function', 1);
		@ini_set('session.hash_bits_per_character', 5);
		session_cache_limiter('nocache');
		session_set_cookie_params(
			$sessionConfig['lifetime'], $sessionConfig['cookie_path'], preg_match('/^[^\\.]+$/', Sr::server('HTTP_HOST')) ? null : $sessionConfig['cookie_domain']
		);
		session_name($sessionConfig['session_name']);
		register_shutdown_function('session_write_close');
		//session托管检测
		$sessionHandle = $config->getSessionHandle();
		if ($sessionHandle && $sessionHandle instanceof Soter_Session) {
			$sessionHandle->init();
		}
		if ($sessionConfig['autostart']) {
			Sr::sessionStart();
		}
		//session初始化完毕

		$class = '';
		$method = '';
		foreach ($config->getRouters() as $router) {
			$route = $router->find($config->getRequest());
			if ($route->found()) {
				$config->setRoute($route);
				$class = $route->getController();
				$method = $route->getMethod();
				break;
			}
		}
		if (empty($route)) {
			throw new Soter_Exception_500('none router was found in configuration');
		}
		$_route = Sr::config()->getRoute();
		//当前域名有绑定hmvc模块,需要处理hmvc模块
		if ($hmvcModuleName = Sr::config()->getHmvcDomain()) {
			if (Soter::checkHmvc($hmvcModuleName, false)) {
				$_route->setHmvcModuleName($hmvcModuleName);
				$_route->setFound(true);
			}
		}
		if (empty($class)) {
			$class = $config->getControllerDirName() . '_' . $config->getDefaultController();
			$_route->setController($class);
		}
		if (empty($method)) {
			$method = $config->getMethodPrefix() . $config->getDefaultMethod();
			$_route->setMethod($method);
		}
		$config->setRoute($_route);
		if (!Sr::classIsExists($class)) {
			throw new Soter_Exception_404('Controller [ ' . $class . ' ] not found');
		}
		$controllerObject = new $class();
		if (!($controllerObject instanceof Soter_Controller)) {
			throw new Soter_Exception_404('[ ' . $class . ' ] not a valid Soter_Controller');
		}
		if (!method_exists($controllerObject, $method)) {
			throw new Soter_Exception_404('Method [ ' . $class . '->' . $method . '() ] not found');
		}

		//前置方法检查执行
		if (method_exists($controllerObject, 'before')) {
			$controllerObject->before(str_replace($config->getMethodPrefix(), '', $method), $route->getArgs());
		}
		//方法缓存检测
		$cacheClassName = preg_replace('/^' . Sr::config()->getControllerDirName() . '_/', '', $class);
		$cacheMethodName = preg_replace('/^' . Sr::config()->getMethodPrefix() . '/', '', $method);
		$methodKey = $cacheClassName . '::' . $cacheMethodName;
		$cacheMethodConfig = $config->getMethodCacheConfig();
		if (!empty($cacheMethodConfig) && Sr::arrayKeyExists($methodKey, $cacheMethodConfig) && $cacheMethodConfig[$methodKey]['cache'] && ($cacheMethoKey = $cacheMethodConfig[$methodKey]['key']())) {
			if (!($contents = Sr::cache()->get($cacheMethoKey))) {
				@ob_start();
				$response = call_user_func_array(array($controllerObject, $method), $route->getArgs());
				$contents = @ob_get_contents();
				@ob_end_clean();
				$contents.=is_array($response) ? Sr::view()->set($response)->load("$cacheClassName/$cacheMethodName") : $response;
				Sr::cache()->set($cacheMethoKey, $contents, $cacheMethodConfig[$methodKey]['time']);
			}
		} else {

			if (method_exists($controllerObject, 'after')) {
				//如果有后置方法，这里应该捕获输出然后传递给后置方法处理
				@ob_start();
				$response = call_user_func_array(array($controllerObject, $method), $route->getArgs());
				$contents = @ob_get_contents();
				@ob_end_clean();
				$contents.=is_array($response) ? Sr::view()->set($response)->load("$cacheClassName/$cacheMethodName") : $response;
			} else {
				$response = call_user_func_array(array($controllerObject, $method), $route->getArgs());
				$contents = is_array($response) ? Sr::view()->set($response)->load("$cacheClassName/$cacheMethodName") : $response;
			}
		}
		//后置方法检查执行
		if (method_exists($controllerObject, 'after')) {
			echo $controllerObject->after(str_replace($config->getMethodPrefix(), '', $method), $route->getArgs(), $contents);
		} else {
			echo $contents;
		}
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
		if (strpos($task, 'Soter_') === 0) {
			$taskName = $task;
		} else {
			$taskName = Soter::getConfig()->getTaskDirName() . '_' . $task;
		}
		if (!class_exists($taskName)) {
			throw new Soter_Exception_500('class [ ' . $taskName . ' ] not found');
		}
		$taskObject = new $taskName();
		if (!($taskObject instanceof Soter_Task)) {
			throw new Soter_Exception_500('[ ' . $taskName . ' ] not a valid Soter_Task');
		}
		$args = Sr::getOpt();
		$args = empty($args) ? array() : $args;
		$taskObject->_execute(new Soter_CliArgs($args));
	}

	/**
	 * 插件模式运行
	 */
	private static function runPlugin() {
		//插件模式
	}

	/**
	 * 检测并加载hmvc模块,成功返回模块文件夹名称，失败返回false或抛出异常
	 * @staticvar array $loadedModules  
	 * @param type $hmvcModuleName  hmvc模块在URI中的名称，即注册配置hmvc模块数组的键名称
	 * @throws Soter_Exception_404
	 */
	public static function checkHmvc($hmvcModuleName, $throwException = true) {
		//hmvc检测
		if (!empty($hmvcModuleName)) {
			$config = Soter::getConfig();
			$hmvcModules = $config->getHmvcModules();
			if (empty($hmvcModules[$hmvcModuleName])) {
				if ($throwException) {
					throw new Soter_Exception_500('Hmvc Module [ ' . $hmvcModuleName . ' ] not found, please check your config.');
				} else {
					return FALSE;
				}
			}
			//避免重复加载，提高性能
			static $loadedModules = array();
			$hmvcModuleDirName = $hmvcModules[$hmvcModuleName];
			if (!Sr::arrayKeyExists($hmvcModuleName, $loadedModules)) {
				$loadedModules[$hmvcModuleName] = 1;
				//找到hmvc模块,去除hmvc模块名称，得到真正的路径
				$hmvcModulePath = $config->getApplicationDir() . $config->getHmvcDirName() . '/' . $hmvcModuleDirName . '/';
				//设置hmvc子项目目录为主目录，同时注册hmvc子项目目录到主包容器，以保证高优先级
				$config->setApplicationDir($hmvcModulePath)->addMasterPackage($hmvcModulePath)->bootstrap();
			}
			return $hmvcModuleDirName;
		}
		return FALSE;
	}

}

class Sr {

	const ENV_TESTING = 1; //测试环境
	const ENV_PRODUCTION = 2; //产品环境
	const ENV_DEVELOPMENT = 3; //开发环境

	static function arrayGet($array, $key, $default = null) {
		$_info = explode('.', $key);
		$keyStrArray = '';
		foreach ($_info as $k) {
			$keyStrArray.= "['{$k}']";
		}
		return eval('return Sr::arrayKeyExists(\'' . implode('.', $_info) . '\',$array)?$array' . $keyStrArray . ':$default;');
	}

	static function dump() {
		echo!self::isCli() ? '<pre style="line-height:1.5em;font-size:14px;">' : "\n";
		@ob_start();
		$args = func_get_args();
		empty($args) ? null : call_user_func_array('var_dump', $args);
		$html = @ob_get_clean();
		echo!self::isCli() ? htmlspecialchars($html) : $html;
		echo!self::isCli() ? "</pre>" : "\n";
	}

	static function includeOnce($filePath) {
		static $includeFiles = array();
		$key = self::realPath($filePath);
		if (!Sr::arrayKeyExists($key, $includeFiles)) {
			include $filePath;
			$includeFiles[$key] = 1;
		}
	}

	static function realPath($path, $addSlash = false) {
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
		return $path . ($addSlash ? '/' : '');
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
		$name = Soter::getConfig()->getBusinessDirName() . '_' . $businessName;
		$object = self::factory($name);
		if (!($object instanceof Soter_Business)) {
			throw new Soter_Exception_500('[ ' . $name . ' ] not a valid Soter_Business');
		}
		return $object;
	}

	static function dao($daoName) {
		$name = Soter::getConfig()->getDaoDirName() . '_' . $daoName;
		$object = self::factory($name);
		if (!($object instanceof Soter_Dao)) {
			throw new Soter_Exception_500('[ ' . $name . ' ] not a valid Soter_Dao');
		}
		return $object;
	}

	static function model($modelName) {
		$name = Soter::getConfig()->getModelDirName() . '_' . $modelName;
		$object = self::factory($name);
		if (!($object instanceof Soter_Model)) {
			throw new Soter_Exception_500('[ ' . $name . ' ] not a valid Soter_Model');
		}
		return $object;
	}

	static function library($className) {
		return self::factory($className);
	}

	static function extension($className) {
		return self::factory('Soter_' . $className);
	}

	static function functions($functionFilename) {
		static $loadedFunctionsFile = array();
		if (Sr::arrayKeyExists($functionFilename, $loadedFunctionsFile)) {
			return;
		} else {
			$loadedFunctionsFile[$functionFilename] = 1;
		}
		$config = Soter::getConfig();
		$found = false;
		foreach ($config->getPackages() as $packagePath) {
			$filePath = $packagePath . $config->getFunctionsDirName() . '/' . $functionFilename . '.php';
			if (file_exists($filePath)) {
				self::includeOnce($filePath);
				$found = true;
				break;
			}
		}
		if (!$found) {
			throw new Soter_Exception_500('functions file [ ' . $functionFilename . '.php ] not found');
		}
	}

	/**
	 * 超级工厂方法
	 * @param type $className      可以是完整的控制器类名，模型类名，类库类名
	 * @param type $hmvcModuleName hmvc模块名称，是配置里面的数组的键名，插件模式下才会用到这个参数
	 * @throws Soter_Exception_404
	 */
	static function factory($className, $hmvcModuleName = null) {
		if (Sr::isPluginMode()) {
			//hmvc检测
			Soter::checkHmvc($hmvcModuleName);
		}
		if (Sr::strEndsWith(strtolower($className), '.php')) {
			$className = substr($className, 0, strlen($className) - 4);
		}
		$className = str_replace('/', '_', $className);
		if (!class_exists($className)) {
			throw new Soter_Exception_500("class [ $className ] not found");
		}
		return new $className();
	}

	/**
	 * 判断是否是插件模式运行
	 * @return type
	 */
	static function isPluginMode() {
		return (defined('SOTER_RUN_MODE_PLUGIN') && SOTER_RUN_MODE_PLUGIN);
	}

	/**
	 * 1.不传递参数返回系统配置对象（Soter_Config）。<br/>
	 * 2.传递参数加载具体的配置<br/>
	 * @staticvar array $loadedConfig
	 * @param type $configName
	 * @return Soter_Config|mixed
	 */
	static function &config($configName = null) {
		if (empty($configName)) {
			return Soter::getConfig();
		}
		$_info = explode('.', $configName);
		$configFileName = current($_info);
		static $loadedConfig = array();
		$cfg = null;
		if (Sr::arrayKeyExists($configFileName, $loadedConfig)) {
			$cfg = $loadedConfig[$configFileName];
		} else {
			$config = Soter::getConfig();
			$found = false;
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
					$found = true;
					break;
				}
			}
			if (!$found) {
				throw new Soter_Exception_500('config file [ ' . $configFileName . '.php ] not found');
			}
		}
		if ($cfg && count($_info) > 1) {
			$val = self::arrayGet($cfg, implode('.', array_slice($_info, 1)));
			return $val;
		} else {
			return $cfg;
		}
	}

	/**
	 * 解析命令行参数 $GLOBALS['argv'] 到一个数组<br>
	 * 参数形式支持:		<br>
	 * -e			<br>
	 * -e <value>		<br>
	 * --long-param		<br>
	 * --long-param=<value><br>
	 * --long-param <value><br>
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
		return empty($key) ? $result : (Sr::arrayKeyExists($key, $result) ? $result[$key] : null);
	}

	static function get($key = null, $default = null, $xssClean = false) {
		$value = is_null($key) ? $_GET : self::arrayGet($_GET, $key, $default);
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function getPost($key, $default = null, $xssClean = false) {
		$getValue = self::arrayGet($_GET, $key);
		$value = is_null($getValue) ? self::arrayGet($_POST, $key, $default) : $getValue;
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function post($key = null, $default = null, $xssClean = false) {
		$value = is_null($key) ? $_POST : self::arrayGet($_POST, $key, $default);
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function postGet($key, $default = null, $xssClean = false) {
		$postValue = self::arrayGet($_POST, $key);
		$value = is_null($postValue) ? self::arrayGet($_GET, $key, $default) : $postValue;
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function session($key = null, $default = null, $xssClean = false) {
		self::sessionStart();
		$value = is_null($key) ? (empty($_SESSION) ? null : $_SESSION) : self::arrayGet($_SESSION, $key, $default);
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function sessionSet($key = null, $value = null) {
		self::sessionStart();
		if (is_array($key)) {
			$_SESSION = array_merge($_SESSION, $key);
		} else {
			$_SESSION[$key] = $value;
		}
	}

	static function server($key = null, $default = null) {
		return is_null($key) ? $_SERVER : self::arrayGet($_SERVER, strtoupper($key), $default);
	}

	/**
	 * 获取原始的POST数据，即php://input获取到的
	 * @return type
	 */
	static function postRawBody() {
		return file_get_contents('php://input');
	}

	/**
	 * 获取一个cookie
	 * 提醒:
	 * 该方法会在key前面加上系统配置里面的getCookiePrefix()
	 * 如果想不加前缀，获取原始key的cookie，可以使用方法：Sr::cookieRaw();
	 * @return type
	 */
	static function cookie($key = null, $default = null, $xssClean = false) {
		$key = is_null($key) ? null : Sr::config()->getCookiePrefix() . $key;
		$value = self::cookieRaw($key, $default, $xssClean);
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function cookieRaw($key = null, $default = null, $xssClean = false) {
		$value = is_null($key) ? $_COOKIE : self::arrayGet($_COOKIE, $key, $default);
		return $xssClean ? self::xssClean($value) : $value;
	}

	/**
	 * 设置一个cookie，该方法会在key前面加上系统配置里面的getCookiePrefix()前缀<br>
	 * 如果不想加前缀，可以使用方法：Sr::setCookieRaw()<br>
	 * 或者设置前缀为空那么Sr::cookie和Sr::cookieRaw效果一样。前缀默认就是空。
	 */
	static function setCookie($key, $value, $life = null, $path = '/', $domian = null, $http_only = false) {
		$key = Sr::config()->getCookiePrefix() . $key;
		return self::setCookieRaw($key, $value, $life, $path, $domian, $http_only);
	}

	static function setCookieRaw($key, $value, $life = null, $path = '/', $domian = null, $httpOnly = false) {
		if (!Sr::isCli()) {
			header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		}
		if (!is_null($domian)) {
			$autoDomain = $domian;
		} else {
			$host = self::server('HTTP_HOST');
			$is_ip = preg_match('/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/', $host);
			$notRegularDomain = preg_match('/^[^\\.]+$/', $host);
			if ($is_ip) {
				$autoDomain = $host;
			} elseif ($notRegularDomain) {
				$autoDomain = NULL;
			} else {
				$autoDomain = '.' . $host;
			}
		}
		setcookie($key, $value, ($life ? $life + time() : null), $path, $autoDomain, (self::server('SERVER_PORT') == 443 ? 1 : 0), $httpOnly);
		$_COOKIE[$key] = $value;
	}

	static function xssClean($var) {
		if (is_array($var)) {
			foreach ($var as $key => $val) {
				if (is_array($val)) {
					$var[$key] = self::xssClean($val);
				} else {
					$var[$key] = self::xssClean0($val);
				}
			}
		} elseif (is_string($var)) {
			$var = self::xssClean0($var);
		}
		return $var;
	}

	private static function xssClean0($data) {
		// Fix &entity\n;
		$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
		do {
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|iframe|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		} while ($old_data !== $data);

		// we are done...
		return $data;
	}

	/**
	 * 服务器的hostname
	 * @return type
	 */
	static function hostname() {
		return function_exists('gethostname') ? gethostname() : (function_exists('php_uname') ? php_uname('n') : 'unknown');
	}

	/**
	 * 服务器的ip
	 * @return type
	 */
	static function serverIp() {
		return self::isCli() ? gethostbyname(self::hostname()) : Sr::server('SERVER_ADDR');
	}

	static function clientIp() {
		if ($ip = self::checkClientIp(Sr::arrayGet($_SERVER, 'HTTP_X_FORWARDED_FOR'))) {
			return $ip;
		} elseif ($ip = self::server('HTTP_CLIENT_IP')) {
			return $ip;
		} elseif ($ip = Sr::arrayGet($_SERVER, 'REMOTE_ADDR')) {
			return $ip;
		} elseif ($ip = self::checkClientIp(getenv("HTTP_X_FORWARDED_FOR"))) {
			return $ip;
		} elseif ($ip = getenv("HTTP_CLIENT_IP")) {
			return $ip;
		} elseif ($ip = getenv("REMOTE_ADDR")) {
			return $ip;
		} else {
			return "Unknown";
		}
	}

	private static function checkClientIp($ip) {
		if (empty($ip)) {
			return false;
		}
		$whitelist = Sr::config()->getBackendServerIpWhitelist();
		foreach ($whitelist as $okayIp) {
			if ($okayIp == $ip) {
				return $ip;
			}
		}
		return FALSE;
	}

	static function strBeginsWith($str, $sub) {
		return ( substr($str, 0, strlen($sub)) == $sub );
	}

	static function strEndsWith($str, $sub) {
		return ( substr($str, strlen($str) - strlen($sub)) == $sub );
	}

	/**
	 * 获取IP段信息<br>
	 * $ipAddr格式：192.168.1.10/24、192.168.1.10/32<br>
	 * 传入Ip地址对Ip段地址进行处理得到相关的信息<br>
	 * 1.没有$key时，返回数组：array(<br>
	 * netmask=>网络掩码<br>
	 * count=>网络可用IP数目<br>
	 * start=>可用IP开始<br>
	 * end=>可用IP结束<br>
	 * netaddress=>网络地址<br>
	 * broadcast=>广播地址<br>
	 * )<br>
	 * 2.有$key时返回$key对应的值，$key是上面数组的键。
	 */
	static function ipInfo($ipAddr, $key = null) {
		$ipAddr = str_replace(" ", "", $ipAddr);    //去除字符串中的空格
		$arr = explode('/', $ipAddr); //对IP段进行解剖

		$ipAddr = $arr[0];    //得到IP地址
		$ipAddrArr = explode('.', $ipAddr);
		foreach ($ipAddrArr as $k => $v) {
			$ipAddrArr[$k] = intval($v); //去掉192.023.20.01其中的023的0
		}
		$ipAddr = implode('.', $ipAddrArr); //修正后的ip地址

		$netbits = intval((Sr::arrayKeyExists(1, $arr) ? $arr[1] : 0));   //得到掩码位

		$subnetMask = long2ip(ip2long("255.255.255.255") << (32 - $netbits));
		$ip = ip2long($ipAddr);
		$nm = ip2long($subnetMask);
		$nw = ($ip & $nm);
		$bc = $nw | (~$nm);

		$ips = array();
		$ips['netmask'] = long2ip($nm);     //网络掩码
		$ips['count'] = ($bc - $nw - 1);      //可用IP数目
		if ($ips['count'] <= 0) {
			$ips['count'] += 4294967296;
		}
		if ($netbits == 32) {
			$ips['count'] = 0;      //当$netbits是32的时候可用数目是-1，这里修正为1
			$ips['start'] = long2ip($ip);    //可用IP开始
			$ips['end'] = long2ip($ip);      //可用IP结束
		} else {
			$ips['start'] = long2ip($nw + 1);    //可用IP开始
			$ips['end'] = long2ip($bc - 1);      //可用IP结束
		}
		$bc = sprintf('%u', $bc);    //或者采用此方法转换成无符号的，修复32位操作系统中long2ip后会出现负数
		$nw = sprintf('%u', $nw);
		$ips['netaddress'] = long2ip($nw);       //网络地址
		$ips['broadcast'] = long2ip($bc);       //广播地址

		return is_null($key) ? $ips : $ips[$key];
	}

	/**
	 * 
	 * 获取数据库操作对象
	 * @staticvar array $instances   数据库单例容器
	 * @param type $group             配置组名称
	 * @param type $isNewInstance     是否刷新单例
	 * @return \Soter_Database_ActiveRecord
	 * @throws Soter_Exception_Database
	 */
	static function &db($group = '', $isNewInstance = false) {
		static $instances = array();
		if (is_array($group)) {
			ksort($group);
			$key = md5(var_export($group, true));
			if (!Sr::arrayKeyExists($key, $instances) || $isNewInstance) {
				$instances[$key] = new Soter_Database_ActiveRecord($group);
			}
			return $instances[$key];
		} else {
			if (empty($group)) {
				$config = self::config()->getDatabseConfig();
				$group = $config['default_group'];
			}
			if (!Sr::arrayKeyExists($group, $instances) || $isNewInstance) {
				$config = self::config()->getDatabseConfig($group);
				if (empty($config)) {
					throw new Soter_Exception_Database('unknown database config group [ ' . $group . ' ]');
				}
				$instances[$group] = new Soter_Database_ActiveRecord($config);
			}
			return $instances[$group];
		}
	}

	static function createSqlite3Database($path) {
		return new PDO('sqlite:' . $path);
	}

	/**
	 * 获取当前UNIX毫秒时间戳
	 * @return float
	 */
	static function microtime() {
		// 获取当前毫秒时间戳
		list ($s1, $s2) = explode(' ', microtime());
		$currentTime = (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
		return $currentTime;
	}

	/**
	 * 屏蔽路径中系统的绝对路径部分，转换为安全的用于显示
	 * @param type $path
	 * @return string
	 */
	static function safePath($path) {
		if (!$path) {
			return '';
		}
		$path = self::realPath($path);
		$siteRoot = self::realPath(self::server('DOCUMENT_ROOT'));
		$_path = str_replace($siteRoot, '', $path);
		$relPath = str_replace($siteRoot, '', rtrim(self::config()->getApplicationDir(), '/'));
		return '~APPPATH~' . str_replace($relPath, '', $_path);
	}

	/**
	 * 获取缓存操作对象
	 * @param type $cacheType
	 * @return Soter_Cache
	 */
	static function cache($cacheType = null) {
		return self::config()->getCacheHandle($cacheType);
	}

	/**
	 * 删除文件夹和子文件夹
	 * @param string $dirPath   文件夹路径
	 * @param type $includeSelf 是否保留最父层文件夹
	 * @return boolean
	 */
	static function rmdir($dirPath, $includeSelf = true) {
		if (empty($dirPath)) {
			return false;
		}
		$dirPath = self::realPath($dirPath) . '/';
		foreach (scandir($dirPath) as $value) {
			if ($value == '.' || $value == '..') {
				continue;
			}
			$path = $dirPath . $value;
			if (is_dir($path)) {
				self::rmdir($path);
				@rmdir($path);
			} else {
				@unlink($path);
			}
		}
		if ($includeSelf) {
			@rmdir($dirPath);
		}
		return true;
	}

	static function view() {
		static $view;
		if (!$view) {
			$view = new Soter_View();
		}
		return $view;
	}

	/**
	 * 获取入口文件所在目录url路径。
	 * 只能在web访问时使用，在命令行下面会抛出异常。
	 * @param type $subpath  子路径或者文件路径，如果非空就会被附加在入口文件所在目录的后面
	 * @return type           
	 * @throws Exception     
	 */
	static function urlPath($subpath = null, $addSlash = true) {
		if (self::isCli()) {
			throw new Soter_Exception_500('urlPath() can not be used in cli mode');
		} else {
			$old_path = getcwd();
			$root = str_replace(array("/", "\\"), '/', self::server('DOCUMENT_ROOT'));
			chdir($root);
			$root = getcwd();
			$root = str_replace(array("/", "\\"), '/', $root);
			chdir($old_path);
			$path = str_replace(array("/", "\\"), '/', realpath('.') . ($subpath ? '/' . trim($subpath, '/\\') : ''));
			$path = self::realPath($path) . ($addSlash ? '/' : '');
			return preg_replace('|^' . self::realPath($root) . '|', '', $path);
		}
	}

	/**
	 * 生成控制器方法的url
	 * @param type $action   控制器方法
	 * @param type $getData  get传递的参数数组，键值对，键是参数名，值是参数值
	 * @return string
	 */
	static function url($action = '', $getData = array()) {
		$config = Sr::config();
		$hmvcModuleName = $config->getHmvcDomain(); //当前域名绑定的hmvc模块名称
		//访问的是hmvc模块且绑定了当前域名，且是DomainOnly的，就去掉开头的模块名称
		if ($hmvcModuleName && $config->hmvcIsDomainOnly($hmvcModuleName)) {
			$action = preg_replace('|^/' . $hmvcModuleName . '/?|', '/', $action);
		}
		$index = self::config()->getIsRewrite() ? '' : self::config()->getIndexName() . '/';
		$url = self::urlPath($index . $action);
		$url = rtrim($url, '/');
		$url = $index ? $url : ($action ? $url : $url . '/');
		if (!empty($getData)) {
			$url = $url . '?';
			foreach ($getData as $k => $v) {
				$url.= $k . '=' . urlencode($v) . '&';
			}
			$url = rtrim($url, '&');
		}
		return $url;
	}

	/**
	 * $source_data和$map的key一致，$map的value是返回数据的key
	 * 根据$map的key读取$source_data中的数据，结果是以map的value为key的数数组
	 * 
	 * @param Array $map 字段映射数组,格式：array('表单name名称'=>'表字段名称',...)
	 */
	static function readData(Array $map, $sourceData = null) {
		$data = array();
		$formdata = is_null($sourceData) ? Sr::post() : $sourceData;
		foreach ($formdata as $formKey => $val) {
			if (Sr::arrayKeyExists($formKey, $map)) {
				$data[$map[$formKey]] = $val;
			}
		}
		return $data;
	}

	static function checkData($data, $rules, &$returnData, &$errorMessage, &$errorKey = null, &$db = null) {
		static $checkRules;
		if (empty($checkRules)) {
			$defaultRules = array(
			    'array' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data) || !is_array($value)) {
					    return false;
				    }
				    $minOkay = true;
				    if (Sr::arrayKeyExists(0, $args)) {
					    $minOkay = count($value) >= intval($args[0]);
				    }
				    $maxOkay = true;
				    if (Sr::arrayKeyExists(1, $args)) {
					    $minOkay = count($value) >= intval($args[1]);
				    }
				    return $minOkay && $maxOkay;
			    }, 'notArray' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    return !is_array($value);
			    }, 'default' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (is_array($value)) {
					    $i = 0;
					    foreach ($value as $k => $v) {

						    $returnValue[$k] = empty($v) ? (Sr::arrayKeyExists($i, $args) ? $args[$i] : $args[0]) : $v;
						    $i++;
					    }
				    } elseif (empty($value)) {
					    $returnValue = $args[0];
				    }
				    return true;
			    }, 'optional' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    $break = !isset($data[$key]);
				    return true;
			    }, 'required' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data) || empty($value)) {
					    return false;
				    }
				    $value = (array) $value;
				    foreach ($value as $v) {
					    if (empty($v)) {
						    return false;
					    }
				    }
				    return true;
			    }, 'requiredKey' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    $args[] = $key;
				    $args = array_unique($args);
				    foreach ($args as $k) {
					    if (!Sr::arrayKeyExists($k, $data)) {
						    return false;
					    }
				    }
				    return true;
			    }, 'functions' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return true;
				    }
				    $returnValue = $value;
				    if (is_array($returnValue)) {
					    foreach ($returnValue as $k => $v) {
						    foreach ($args as $function) {
							    $returnValue[$k] = $function($v);
						    }
					    }
				    } else {
					    foreach ($args as $function) {
						    $returnValue = $function($returnValue);
					    }
				    }
				    return true;
			    }, 'xss' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return true;
				    }
				    $returnValue = Sr::xssClean($value);
				    return true;
			    }, 'match' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data) || !Sr::arrayKeyExists(0, $args) || !Sr::arrayKeyExists($args[0], $data) || $value != $data[$args[0]]) {
					    return false;
				    }
				    return true;
			    }, 'equal' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data) || !Sr::arrayKeyExists(0, $args) || $value != $args[0]) {
					    return false;
				    }
				    return true;
			    }, 'enum' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $value = (array) $value;
				    foreach ($value as $v) {
					    if (!in_array($v, $args)) {
						    return false;
					    }
				    }
				    return true;
			    }, 'unique' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #比如unique[user.name] , unique[user.name,id:1]
				    if (!Sr::arrayKeyExists($key, $data) || !$value || !count($args)) {
					    return false;
				    }
				    $_info = explode('.', $args[0]);
				    if (count($_info) != 2) {
					    return false;
				    }
				    $table = $_info[0];
				    $col = $_info[1];
				    if (Sr::arrayKeyExists(1, $args)) {
					    $_id_info = explode(':', $args[1]);
					    if (count($_id_info) != 2) {
						    return false;
					    }
					    $id_col = $_id_info[0];
					    $id = $_id_info[1];
					    $id = stripos($id, '#') === 0 ? Sr::getPost(substr($id, 1)) : $id;
					    $where = array($col => $value, "$id_col <>" => $id);
				    } else {
					    $where = array($col => $value);
				    }
				    return !$db->where($where)->from($table)->execute()->total();
			    }, 'exists' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #比如exists[user.name] , exists[user.name,type:1], exists[user.name,type:1,sex:#sex]
				    if (!Sr::arrayKeyExists($key, $data) || !$value || !count($args)) {
					    return false;
				    }
				    $_info = explode('.', $args[0]);
				    if (count($_info) != 2) {
					    return false;
				    }
				    $table = $_info[0];
				    $col = $_info[1];
				    $where = array($col => $value);
				    if (count($args) > 1) {
					    foreach (array_slice($args, 1) as $v) {
						    $_id_info = explode(':', $v);
						    if (count($_id_info) != 2) {
							    continue;
						    }
						    $id_col = $_id_info[0];
						    $id = $_id_info[1];
						    $id = stripos($id, '#') === 0 ? Sr::getPost(substr($id, 1)) : $id;
						    $where[$id_col] = $id;
					    }
				    }
				    return $db->where($where)->from($table)->execute()->total();
			    }, 'min_len' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = Sr::arrayKeyExists(0, $args) ? (mb_strlen($value, 'UTF-8') >= intval($args[0])) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'max_len' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = Sr::arrayKeyExists(0, $args) ? (mb_strlen($value, 'UTF-8') <= intval($args[0])) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'range_len' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = count($args) == 2 ? (mb_strlen($value, 'UTF-8') >= intval($args[0])) && (mb_strlen($value, 'UTF-8') <= intval($args[1])) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'len' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = Sr::arrayKeyExists(0, $args) ? (mb_strlen($value, 'UTF-8') == intval($args[0])) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'min' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = Sr::arrayKeyExists(0, $args) && is_numeric($value) ? $value >= $args[0] : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'max' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = Sr::arrayKeyExists(0, $args) && is_numeric($value) ? $value <= $args[0] : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'range' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = (count($args) == 2) && is_numeric($value) ? $value >= $args[0] && $value <= $args[1] : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'alpha' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    #纯字母
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !preg_match('/[^A-Za-z]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'alpha_num' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #纯字母和数字
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !preg_match('/[^A-Za-z0-9]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'alpha_dash' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #纯字母和数字和下划线和-
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !preg_match('/[^A-Za-z0-9_-]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'alpha_start' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #以字母开头
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^[A-Za-z]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'num' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #纯数字
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !preg_match('/[^0-9]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'int' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #整数
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^([-+]?[1-9]\d*|0)$/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'float' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #小数
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^([1-9]\d*|0)\.\d+$/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'numeric' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #数字-1，1.2，+3，4e5
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = is_numeric($value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'natural' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #自然数0，1，2，3，12，333
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^([1-9]\d*|0)$/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'natural_no_zero' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #自然数不包含0
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^[1-9]\d*$/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'email' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'url' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'qq' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[1-9][0-9]{4,}$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'phone' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^(?:\d{3}-?\d{8}|\d{4}-?\d{7})$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'mobile' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1}))+\d{8})$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'zipcode' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[1-9]\d{5}(?!\d)$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'idcard' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^\d{14}(\d{4}|(\d{3}[xX])|\d{1})$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'ip' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'chs' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $count = implode(',', array_slice($args, 1, 2));
				    $count = empty($count) ? '1,' : $count;
				    $can_empty = Sr::arrayKeyExists(0, $args) && $args[0] == 'true';
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[\x{4e00}-\x{9fa5}]{' . $count . '}$/u', $value) : $can_empty;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'date' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'time' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^(([0-1][0-9])|([2][0-3])):([0-5][0-9])(:([0-5][0-9]))$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'datetime' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $args[0] = Sr::arrayKeyExists(0, $args) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30))) (([0-1][0-9])|([2][0-3])):([0-5][0-9])(:([0-5][0-9]))$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'reg' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!Sr::arrayKeyExists($key, $data)) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($args[0]) ? preg_match($args[0], $value) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }
				);
				$userRules = Sr::config()->getDataCheckRules();
				$checkRules = (is_array($userRules) && !empty($userRules)) ? array_merge($defaultRules, $userRules) : $defaultRules;
			}
			$getCheckRuleInfo = function($_rule) {
				$matches = array();
				preg_match('|([^\[]+)(?:\[(.*)\](.?))?|', $_rule, $matches);
				$matches[1] = Sr::arrayKeyExists(1, $matches) ? $matches[1] : '';
				$matches[3] = !empty($matches[3]) ? $matches[3] : ',';
				$matches[2] = Sr::arrayKeyExists(2, $matches) ? explode($matches[3], $matches[2]) : array();
				return $matches;
			};
			$returnData = $data;
			foreach ($rules as $key => $keyRules) {
				foreach ($keyRules as $rule => $message) {
					$matches = $getCheckRuleInfo($rule);
					$_v = self::arrayGet($returnData, $key);
					$_r = $matches[1];
					$args = $matches[2];
					if (!Sr::arrayKeyExists($_r, $checkRules) || !is_callable($checkRules[$_r])) {
						throw new Soter_Exception_500('error rule [ ' . $_r . ' ]');
					}
					$ruleFunction = $checkRules[$_r];
					$db = (is_object($db) && ($db instanceof Soter_Database_ActiveRecord) ) ? $db : Sr::db();
					$break = false;
					$returnValue = null;
					$isOkay = $ruleFunction($key, $_v, $data, $args, $returnValue, $break, $db);
					if (!$isOkay) {
						$errorMessage = $message;
						$errorKey = $key;
						return false;
					}
					if (!is_null($returnValue)) {
						$returnData[$key] = $returnValue;
					}
					if ($break) {
						break;
					}
				}
			}
			return true;
		}

		static function sessionStart() {
			if (!self::isCli()) {
				$started = false;
				if (version_compare(phpversion(), '5.4.0', '>=')) {
					$started = session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
				} else {
					$started = session_id() === '' ? FALSE : TRUE;
				}
				if (!$started && !headers_sent()) {
					@session_start();
				}
			}
		}

		/**
		 * 分页方法
		 * @param type $total 一共多少记录
		 * @param type $page  当前是第几页
		 * @param type $pagesize 每页多少
		 * @param type $url    url是什么，url里面的{page}会被替换成页码
		 * @param array $order 分页条的组成，是一个数组，可以按着1-6的序号，选择分页条组成部分和每个部分的顺序
		 * @param int $a_count   分页条中a页码链接的总数量,不包含当前页的a标签，默认10个。
		 * @return type  String
		 * echo Sr::page(100,3,10,'?article/list/{page}',array(3,4,5,1,2,6));
		 */
		static function page($total, $page, $pagesize, $url, $order = array(1, 2, 3, 4, 5, 6), $a_count = 10) {
			$a_num = $a_count;
			$first = '首页';
			$last = '尾页';
			$pre = '上页';
			$next = '下页';
			$a_num = $a_num % 2 == 0 ? $a_num + 1 : $a_num;
			$pages = ceil($total / $pagesize);
			$curpage = intval($page) ? intval($page) : 1;
			$curpage = $curpage > $pages || $curpage <= 0 ? 1 : $curpage; #当前页超范围置为1
			$body = '<span class="page_body">';
			$prefix = '';
			$subfix = '';
			$start = $curpage - ($a_num - 1) / 2; #开始页
			$end = $curpage + ($a_num - 1) / 2;  #结束页
			$start = $start <= 0 ? 1 : $start;   #开始页超范围修正
			$end = $end > $pages ? $pages : $end; #结束页超范围修正
			if ($pages >= $a_num) {#总页数大于显示页数
				if ($curpage <= ($a_num - 1) / 2) {
					$end = $a_num;
				}//当前页在左半边补右边
				if ($end - $curpage <= ($a_num - 1) / 2) {
					$start-=floor($a_num / 2) - ($end - $curpage);
				}//当前页在右半边补左边
			}
			for ($i = $start; $i <= $end; $i++) {
				if ($i == $curpage) {
					$body.='<a class="page_cur_page" href="javascript:void(0);"><b>' . $i . '</b></a>';
				} else {
					$body.='<a href="' . str_replace('{page}', $i, $url) . '">' . $i . '</a>';
				}
			}
			$body.='</span>';
			$prefix = ($curpage == 1 ? '' : '<span class="page_bar_prefix"><a href="' . str_replace('{page}', 1, $url) . '">' . $first . '</a><a href="' . str_replace('{page}', $curpage - 1, $url) . '">' . $pre . '</a></span>');
			$subfix = ($curpage == $pages ? '' : '<span class="page_bar_subfix"><a href="' . str_replace('{page}', $curpage + 1, $url) . '">' . $next . '</a><a href="' . str_replace('{page}', $pages, $url) . '">' . $last . '</a></span>');
			$info = "<span class=\"page_cur\">第{$curpage}/{$pages}页</span>";
			$id = "gsd09fhas9d" . rand(100000, 1000000);
			$go = '<script>function ekup(){if(event.keyCode==13){clkyup();}}function clkyup(){var num=document.getElementById(\'' . $id . '\').value;if(!/^\d+$/.test(num)||num<=0||num>' . $pages . '){alert(\'请输入正确页码!\');return;};location=\'' . addslashes($url) . '\'.replace(/\\{page\\}/,document.getElementById(\'' . $id . '\').value);}</script><span class="page_input_num"><input onkeyup="ekup()" type="text" id="' . $id . '" style="width:40px;vertical-align:text-baseline;padding:0 2px;font-size:10px;border:1px solid gray;"/></span><span class="page_btn_go" onclick="clkyup();" style="cursor:pointer;">转到</span>';
			$total = "<span class=\"page_total\">共{$total}条</span>";
			$pagination = array(
			    $total,
			    $info,
			    $prefix,
			    $body,
			    $subfix,
			    $go
			);
			$output = array();
			if (is_null($order)) {
				$order = array(1, 2, 3, 4, 5, 6);
			}
			foreach ($order as $key) {
				if (Sr::arrayKeyExists($key - 1, $pagination)) {
					$output[] = $pagination[$key - 1];
				}
			}
			return $pages > 1 ? implode("", $output) : '';
		}

		static function json() {
			$args = func_get_args();
			$handle = Sr::config()->getOutputJsonRender();
			if (is_callable($handle)) {
				return call_user_func_array($handle, $args);
			} else {
				return '';
			}
		}

		static function redirect($url, $msg = null, $time = 3, $view = null) {
			if (empty($msg) && empty($view)) {
				header('Location: ' . $url);
			} else {
				$time = intval($time) ? intval($time) : 3;
				header("refresh:{$time};url={$url}"); //单位秒
				header("Content-type: text/html; charset=utf-8");
				if (empty($view)) {
					echo $msg;
				} else {
					self::view()->set(array('msg' => $msg, 'url' => $url, 'time' => $time))->load($view);
				}
			}
			exit();
		}

		static function message($msg, $url = null, $time = 3, $view = null) {
			$time = intval($time) ? intval($time) : 3;
			if (!empty($url)) {
				header("refresh:{$time};url={$url}"); //单位秒
			}
			header("Content-type: text/html; charset=utf-8");
			if (!empty($view)) {
				self::view()->set(array('msg' => $msg, 'url' => $url, 'time' => $time))->load($view);
			} else {
				echo $msg;
			}
			exit();
		}

		public static function __callStatic($name, $arguments) {
			$methods = self::config()->getSrMethods();
			if (empty($methods[$name])) {
				throw new soter_exception_500($name . ' not found in ->setSrMethods() or it is empty');
			}
			if (is_string($methods[$name])) {
				$className = $methods[$name] . '_' . self::arrayGet($arguments, 0);
				if ($className) {
					return Sr::factory($className);
				} else {
					throw new soter_exception_500($methods[$name] . '() need argument of class name ');
				}
			} elseif (is_callable($methods[$name])) {
				return call_user_func_array($methods[$name], $arguments);
			} else {
				throw new soter_exception_500($name . ' unknown type of method [ ' . $name . ' ]');
			}
		}

		static function arrayKeyExists($key, $array) {
			if (empty($array) || !is_array($array)) {
				return false;
			}
			$keys = explode('.', $key);
			while (count($keys) != 0) {
				if (empty($array) || !is_array($array)) {
					return false;
				}
				$key = array_shift($keys);
				if (!array_key_exists($key, $array)) {
					return false;
				}
				$array = $array[$key];
			}
			return true;
		}

		private static function getEncryptKey($key, $attachKey) {
			$_key = $key ? $key : self::config()->getEncryptKey();
			if (!$key && !$_key) {
				throw new Soter_Exception_500('encrypt key can not empty or you can set it in index.php : ->setEncryptKey()');
			}
			return substr(md5($_key . $attachKey), 0, 8);
		}

		static function encrypt($str, $key = '', $attachKey = '') {
			if (!$str) {
				return '';
			}
			$str = $str . '';
			$key = self::getEncryptKey($key, $attachKey);
			$block = mcrypt_get_block_size('des', 'ecb');
			$pad = $block - (strlen($str) % $block);
			$str .= str_repeat(chr($pad), $pad);
			return bin2hex(mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB));
		}

		static function decrypt($str, $key = '', $attachKey = '') {
			if (!$str) {
				return '';
			}
			$str = $str . '';
			$key = self::getEncryptKey($key, $attachKey);
			$str = @pack("H*", $str);
			if (!$str) {
				return '';
			}
			$str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
			$pad = ord($str[($len = strlen($str)) - 1]);
			return substr($str, 0, strlen($str) - $pad);
		}

		static function classIsExists($class) {
			if (class_exists($class, false)) {
				return true;
			}
			$classNamePath = str_replace('_', '/', $class);
			foreach (self::config()->getPackages() as $path) {
				if (file_exists($filePath = $path . self::config()->getClassesDirName() . '/' . $classNamePath . '.php')) {
					return true;
				}
			}
			return false;
		}

		/**
		 * 判断是否是ajax请求，只对jquery的ajax请求有效
		 * @return boolean
		 */
		static function isAjax() {
			return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
		}

		/**
		 * 获取系统临时目录路径
		 * @return type
		 */
		public function getTempPath() {
			$path = '';
			if (!function_exists('sys_get_temp_dir')) {
				if (!empty($_ENV['TMP'])) {
					$path = realpath($_ENV['TMP']);
				} elseif (!empty($_ENV['TMPDIR'])) {
					$path = realpath($_ENV['TMPDIR']);
				} elseif (!empty($_ENV['TEMP'])) {
					$path = realpath($_ENV['TEMP']);
				} else {
					$tempfile = tempnam(uniqid(rand(), TRUE), '');
					if (file_exists($tempfile)) {
						unlink($tempfile);
						$path = realpath(dirname($tempfile));
					}
				}
			} else {
				$path = sys_get_temp_dir();
			}
			return $path ? $path . '/' : '';
		}

	}
	