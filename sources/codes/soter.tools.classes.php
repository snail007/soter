<?php

class Soter_Request {

	private $uri;

	public function __construct($uri = '') {
		$this->setUri($uri);
	}

	public function setUri($uri) {
		$this->uri = $uri;
		return $this;
	}

	public function getUri() {
		return $this->uri;
	}

}

class Soter_View {

	private static $vars = array();

	public function add($key, $value = array()) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				if (!isset(self::$vars[$k])) {
					self::$vars[$k] = $v;
				}
			}
		} else {
			if (!isset(self::$vars[$key])) {
				self::$vars[$key] = $value;
			}
		}
		return $this;
	}

	public function set($key, $value = array()) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				self::$vars[$k] = $v;
			}
		} else {
			self::$vars[$key] = $value;
		}
		return $this;
	}

	private function _load($path, $data = array(), $return = false) {
		if (!file_exists($path)) {
			throw new Soter_Exception_500('view file : [ ' . $path . ' ] not found');
		}
		$data = array_merge(self::$vars, $data);
		if (!empty($data)) {
			extract($data);
		}
		if ($return) {
			@ob_start();
			include $path;
			$html = ob_get_contents();
			@ob_end_clean();
			return $html;
		} else {
			include $path;
			return;
		}
	}

	/**
	 * 加载一个视图<br/>
	 * @param string $viewName 视图名称
	 * @param array  $data     视图中可以使用的数据
	 * @param bool   $return   是否返回视图内容
	 * @return string
	 */
	public function load($viewName, $data = array(), $return = false) {
		$config = Sr::config();
		$path = $config->getApplicationDir() . $config->getViewsDirName() . '/' . $viewName . '.php';
		$hmvcModules = $config->getHmvcModules();
		$hmvcDirName = Sr::arrayGet($hmvcModules, $config->getRoute()->getHmvcModuleName(), '');
		//当load方法在主项目的视图中被调用，然后hmvc主项目load了这个视图，那么这个视图里面的load应该使用的是主项目视图。
		//hmvc访问
		if ($hmvcDirName) {
			$trace = debug_backtrace();
			$calledFilePath = array_shift($trace);
			$calledFilePath = Sr::realPath(Sr::arrayGet($calledFilePath, 'file'));
			$hmvcPath = $config->getPrimaryApplicationDir() . $config->getHmvcDirName() . '/' . $hmvcDirName;
			$calledIsInHmvc = $calledFilePath && $hmvcDirName && (strpos($calledFilePath, $hmvcPath) === 0);
			//发现load是在主项目中被调用的，使用主项目视图
			if (!$calledIsInHmvc) {
				$path = $config->getPrimaryApplicationDir() . $config->getViewsDirName() . '/' . $viewName . '.php';
			}
		}
		return $this->_load($path, $data, $return);
	}

	/**
	 * 加载主项目的视图<br/>
	 * 这个一般是在hmvc模块中使用到，用于复用主项目的视图文件，比如通用的header等。<br/>
	 * @param string $viewName 主项目视图名称
	 * @param array  $data     视图中可以使用的数据
	 * @param bool   $return   是否返回视图内容
	 * @return string
	 */
	public function loadParent($viewName, $data = array(), $return = false) {
		$config = Sr::config();
		$path = $config->getPrimaryApplicationDir() . $config->getViewsDirName() . '/' . $viewName . '.php';
		return $this->_load($path, $data, $return);
	}

}

class Soter_CliArgs {

	private $args;

	public function __construct() {
		$this->args = Sr::getOpt();
	}

	public function get($key = null, $default = null) {
		if (empty($key)) {
			return $this->args;
		}
		return Sr::arrayGet($this->args, $key, $default);
	}

}

class Soter_Route {

	private $found = false;
	private $controller, $method, $args, $hmvcModuleName;

	public function getHmvcModuleName() {
		return $this->hmvcModuleName;
	}

	public function setHmvcModuleName($hmvcModuleName) {
		$this->hmvcModuleName = $hmvcModuleName;
		return $this;
	}

	public function found() {
		return $this->found;
	}

	public function setFound($found) {
		$this->found = $found;
		return $this;
	}

	public function getController() {
		return $this->controller;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getArgs() {
		return $this->args;
	}

	public function __construct() {
		$this->args = array();
	}

	public function setController($controller) {
		$this->controller = $controller;
		return $this;
	}

	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}

	public function setArgs(array $args) {
		$this->args = $args;
		return $this;
	}

}

class Soter_Default_Router_Get extends Soter_Router {

	public function find() {
		$config = Sr::config();
		$uri = explode('?', $config->getRequest()->getUri());
		$query = end($uri);
		parse_str($query, $get);
		$controllerName = Sr::arrayGet($get, $config->getRouterUrlControllerKey(), '');
		$methodName = Sr::arrayGet($get, $config->getRouterUrlMethodKey(), '');
		$hmvcModuleName = Sr::arrayGet($get, $config->getRouterUrlModuleKey(), '');
		//hmvc检测
		$hmvcModuleDirName = Soter::checkHmvc($hmvcModuleName, false);
		if ($controllerName) {
			$controllerName = $config->getControllerDirName() . '_' . $controllerName;
		}
		if ($methodName) {
			$methodName = $config->getMethodPrefix() . $methodName;
		}
		return $this->route->setHmvcModuleName($hmvcModuleDirName ? $hmvcModuleName : '')
				->setController($controllerName)
				->setMethod($methodName)
				->setFound($hmvcModuleDirName || $controllerName);
	}

}

class Soter_Default_Router_PathInfo extends Soter_Router {

	/**
	 * 只处理pathinfo模式的路由<br>
	 * 比如：<br>
	 * uri：/index.php/Vip uri至少有一个hmvc模块名称Vip，或者控制器名称Vip<br>
	 * 如果没有就认为不是pathinfo模式的路由<br>
	 * @return \Soter_Route
	 */
	public function find() {
		$config = Soter::getConfig();
		/**
		 * 获取uri
		 * pathinfo模式路由判断以及解析uri中的访问路径 
		 * 比如：http://127.0.0.1/index.php/Welcome/index.do?id=11
		 * 获取的是后面的(Welcome/index.do)部分，也就是index.php/和?之间的部分
		 */
		$uri = $config->getRequest()->getUri();
		if (empty($uri)) {
			//没有找到hmvc模块名称，或者控制器名称
			return $this->route->setFound(FALSE);
		} else {
			if ($uriRewriter = $config->getUriRewriter()) {
				$uri = $uriRewriter->rewrite($uri);
			}
		}
		$uri = trim($uri, '/');
		//到此$uri形如：Welcome/index.do , Welcome/User , Welcome
		$_info = explode('/', $uri);
		$hmvcModule = current($_info);
		//hmvc检测 ，Soter::checkHmvc()执行后，主配置会被hmvc子项目配置覆盖
		if ($hmvcModuleDirName = Soter::checkHmvc($hmvcModule, FALSE)) {
			//找到hmvc模块,去除hmvc模块名称，得到真正的路径
			$uri = ltrim(substr($uri, strlen($hmvcModule)), '/');
		}
		//首先控制器名和方法名初始化为默认
		$controller = $config->getDefaultController();
		$method = $config->getDefaultMethod();
		$subfix = $config->getMethodUriSubfix();

		/**
		 * 到此，如果上面$uri被去除掉hmvc模块名称后，$uri有可能是空
		 * 或者$uri有控制器名称或者方法-参数名称
		 * 形如：1.Welcome/article-001.do , 2.Welcome/article-001.do , 
		 *      3.article-001.do ,4.article.do , 5.Welcome/User , 6.Welcome 
		 */
		if ($uri) {
			//解析路径
			$methodPathArr = explode($subfix, $uri);
			//找到了控制器名或者方法-参数名(1,2,3,4)
			if (Sr::strEndsWith($uri, $subfix)) {
				//找到了控制器名和方法-参数名(1,2)，覆盖上面的默认控制器名和方法-参数名
				if (stripos($methodPathArr[0], '/') !== false) {
					$controller = str_replace('/', '_', dirname($uri));
					$method = basename($methodPathArr[0]);
				} else {
					//只找到了方法-参数名(3,4)，覆盖上面的默认方法名
					$method = basename($methodPathArr[0]);
				}
			} else {
				//只找到了控制器名(5,6)，覆盖上面的默认控制器名
				$controller = str_replace('/', '_', $uri);
			}
		}
		$controller = $config->getControllerDirName() . '_' . $controller;
		//统一解析方法-参数名
		$methodAndParameters = explode($config->getMethodParametersDelimiter(), $method);
		$method = $config->getMethodPrefix() . current($methodAndParameters);
		array_shift($methodAndParameters);
		$parameters = $methodAndParameters;
		//$config->getMethodPrefix() . $method;
		return $this->route
				->setHmvcModuleName($hmvcModuleDirName ? $hmvcModule : '')
				->setController($controller)
				->setMethod($method)
				->setArgs($parameters)
				->setFound(TRUE);
	}

}

/**
 * @property Soter_Exception_Handle $exceptionHandle
 */
class Soter_Config {

	private $applicationDir = '', //项目目录
		$primaryApplicationDir = '', //主项目目录
		$indexDir = '', //入口文件目录
		$indexName = '', //入口文件名称
		$classesDirName = 'classes',
		$hmvcDirName = 'hmvc',
		$libraryDirName = 'library',
		$functionsDirName = 'functions',
		$viewsDirName = 'views',
		$configDirName = 'config',
		$configTestingDirName = 'testing',
		$configProductionDirName = 'production',
		$configDevelopmentDirName = 'development',
		$controllerDirName = 'Controller',
		$businessDirName = 'Business',
		$daoDirName = 'Dao',
		$beanDirName = 'Bean',
		$modelDirName = 'Model',
		$taskDirName = 'Task',
		$defaultController = 'Welcome',
		$defaultMethod = 'index',
		$methodPrefix = 'do_',
		$methodUriSubfix = '.do',
		$routerUrlModuleKey = 'm',
		$routerUrlControllerKey = 'c',
		$routerUrlMethodKey = 'a',
		$methodParametersDelimiter = '-',
		$logsSubDirNameFormat = 'Y-m-d/H',
		$cookiePrefix = '',
		$backendServerIpWhitelist = '',
		$isRewrite = FALSE,
		$request, $showError = true,
		$routersContainer = array(),
		$packageMasterContainer = array(),
		$packageContainer = array(),
		$loggerWriterContainer = array(),
		$uriRewriter,
		$exceptionHandle, $route, $environment = Sr::ENV_DEVELOPMENT,
		$serverEnvironmentTestingValue = 'testing',
		$serverEnvironmentDevelopmentValue = 'development',
		$serverEnvironmentProductionValue = 'production',
		$hmvcModules = array(),
		$isMaintainMode = false,
		$maintainIpWhitelist = array(),
		$maintainModeHandle,
		$databseConfig,
		$cacheHandle,
		$sessionConfig,
		$sessionHandle,
		$methodCacheConfig,
		$dataCheckRules,
		$outputJsonRender,
		$exceptionJsonRender

	;

	public function getExceptionJsonRender() {
		return $this->exceptionJsonRender;
	}

	public function setExceptionJsonRender($exceptionJsonRender) {
		$this->exceptionJsonRender = $exceptionJsonRender;
		return $this;
	}

	public function getOutputJsonRender() {
		return $this->outputJsonRender;
	}

	public function setOutputJsonRender($outputJsonHandle) {
		$this->outputJsonRender = $outputJsonHandle;
		return $this;
	}

	public function getDataCheckRules() {
		return $this->dataCheckRules;
	}

	public function setDataCheckRules($dataCheckRules) {
		$this->dataCheckRules = is_array($dataCheckRules) ? $dataCheckRules : Sr::config($dataCheckRules);
		return $this;
	}

	public function getMethodCacheConfig() {
		return $this->methodCacheConfig;
	}

	public function setMethodCacheConfig($methodCacheConfig) {
		$this->methodCacheConfig = is_array($methodCacheConfig) ? $methodCacheConfig : Sr::config($methodCacheConfig);
		return $this;
	}

	public function getViewsDirName() {
		return $this->viewsDirName;
	}

	public function setViewsDirName($viewsDirName) {
		$this->viewsDirName = $viewsDirName;
		return $this;
	}

	/**
	 * 
	 * @return Soter_Cache
	 */
	public function getCacheHandle() {
		return $this->cacheHandle;
	}

	public function setCacheHandle($cacheHandle) {
		if ($cacheHandle instanceof Soter_Cache) {
			$this->cacheHandle = $cacheHandle;
		} else {
			$this->cacheHandle = Sr::config($cacheHandle);
		}
		return $this;
	}

	/**
	 * 
	 * @return Soter_Session
	 */
	public function getSessionHandle() {
		return $this->sessionHandle;
	}

	public function setSessionHandle($sessionHandle) {

		if ($sessionHandle instanceof Soter_Session) {
			$this->sessionHandle = $sessionHandle;
		} else {
			$this->sessionHandle = Sr::config($sessionHandle);
		}
		return $this;
	}

	public function getSessionConfig() {
		if (empty($this->sessionConfig)) {
			$this->sessionConfig = array(
			    'autostart' => false,
			    'cookie_path' => '/',
			    'cookie_domain' => Sr::server('HTTP_HOST'),
			    'session_name' => 'SOTER',
			    'lifetime' => 3600,
			);
		}
		return $this->sessionConfig;
	}

	public function setSessionConfig($sessionConfig) {
		if (is_array($sessionConfig)) {
			$this->sessionConfig = $sessionConfig;
		} else {
			$this->sessionConfig = Sr::config($sessionConfig);
		}
		return $this;
	}

	public function getDatabseConfig($group = null) {
		if (empty($group)) {
			return $this->databseConfig;
		} else {
			return isset($this->databseConfig[$group]) ? $this->databseConfig[$group] : array();
		}
	}

	public function setDatabseConfig($databseConfig) {
		$this->databseConfig = is_array($databseConfig) ? $databseConfig : Sr::config($databseConfig);
		return $this;
	}

	public function getIsMaintainMode() {
		return $this->isMaintainMode;
	}

	public function getMaintainModeHandle() {
		return $this->maintainModeHandle;
	}

	public function setIsMaintainMode($isMaintainMode) {
		$this->isMaintainMode = $isMaintainMode;
		return $this;
	}

	public function setMaintainModeHandle(Soter_Maintain_Handle $maintainModeHandle) {
		$this->maintainModeHandle = $maintainModeHandle;
		return $this;
	}

	public function getMaintainIpWhitelist() {
		return $this->maintainIpWhitelist;
	}

	public function setMaintainIpWhitelist($maintainIpWhitelist) {
		$this->maintainIpWhitelist = $maintainIpWhitelist;
		return $this;
	}

	public function getMethodParametersDelimiter() {
		return $this->methodParametersDelimiter;
	}

	public function setMethodParametersDelimiter($methodParametersDelimiter) {
		$this->methodParametersDelimiter = $methodParametersDelimiter;
		return $this;
	}

	public function getRouterUrlModuleKey() {
		return $this->routerUrlModuleKey;
	}

	public function getRouterUrlControllerKey() {
		return $this->routerUrlControllerKey;
	}

	public function getRouterUrlMethodKey() {
		return $this->routerUrlMethodKey;
	}

	public function setRouterUrlModuleKey($routerUrlModuleKey) {
		$this->routerUrlModuleKey = $routerUrlModuleKey;
		return $this;
	}

	public function setRouterUrlControllerKey($routerUrlControllerKey) {
		$this->routerUrlControllerKey = $routerUrlControllerKey;
		return $this;
	}

	public function setRouterUrlMethodKey($routerUrlMethodKey) {
		$this->routerUrlMethodKey = $routerUrlMethodKey;
		return $this;
	}

	/**
	 * 
	 * @return Soter_Uri_Rewriter
	 */
	public function getUriRewriter() {
		return $this->uriRewriter;
	}

	public function setUriRewriter(Soter_Uri_Rewriter $uriRewriter) {
		$this->uriRewriter = $uriRewriter;
		return $this;
	}

	public function getPrimaryApplicationDir() {
		return $this->primaryApplicationDir;
	}

	public function setPrimaryApplicationDir($primaryApplicationDir) {
		$this->primaryApplicationDir = Sr::realPath($primaryApplicationDir) . '/';
		return $this;
	}

	public function getBackendServerIpWhitelist() {
		return $this->backendServerIpWhitelist;
	}

	/**
	 * 如果服务器是ngix之类代理转发请求到后端apache运行的PHP<br>
	 * 那么这里应该设置信任的nginx所在服务器的ip<br>
	 * nginx里面应该设置 X_FORWARDED_FOR server变量来表示真实的客户端IP<br>
	 * 不然通过Sr::clientIp()是获取不到真实的客户端IP的<br>
	 * @param type $backendServerIpWhitelist
	 * @return \Soter_Config
	 */
	public function setBackendServerIpWhitelist(Array $backendServerIpWhitelist) {
		$this->backendServerIpWhitelist = $backendServerIpWhitelist;
		return $this;
	}

	public function getCookiePrefix() {
		return $this->cookiePrefix;
	}

	public function setCookiePrefix($cookiePrefix) {
		$this->cookiePrefix = $cookiePrefix;
		return $this;
	}

	public function getLogsSubDirNameFormat() {
		return $this->logsSubDirNameFormat;
	}

	/**
	 * 设置日志子目录格式，参数就是date()函数的第一个参数,默认是 Y-m-d/H
	 * @param type $logsSubDirNameFormat
	 * @return \Soter_Config
	 */
	public function setLogsSubDirNameFormat($logsSubDirNameFormat) {
		$this->logsSubDirNameFormat = $logsSubDirNameFormat;
		return $this;
	}

	public function addAutoloadFunctions(Array $funciontsFileNameArray) {
		foreach ($funciontsFileNameArray as $functionsFileName) {
			Sr::functions($functionsFileName);
		}
		return $this;
	}

	public function getFunctionsDirName() {
		return $this->functionsDirName;
	}

	public function setFunctionsDirName($functionsDirName) {
		$this->functionsDirName = $functionsDirName;
		return $this;
	}

	public function getModelDirName() {
		return $this->modelDirName;
	}

	public function setModelDirName($modelDirName) {
		$this->modelDirName = $modelDirName;
		return $this;
	}

	public function getBeanDirName() {
		return $this->beanDirName;
	}

	public function setBeanDirName($beanDirName) {
		$this->beanDirName = $beanDirName;
		return $this;
	}

	public function getBusinessDirName() {
		return $this->businessDirName;
	}

	public function getDaoDirName() {
		return $this->daoDirName;
	}

	public function getTaskDirName() {
		return $this->taskDirName;
	}

	public function setBusinessDirName($businessDirName) {
		$this->businessDirName = $businessDirName;
		return $this;
	}

	public function setDaoDirName($daoDirName) {
		$this->daoDirName = $daoDirName;
		return $this;
	}

	public function setTaskDirName($taskDirName) {
		$this->taskDirName = $taskDirName;
		return $this;
	}

	public function getServerEnvironment($environment) {
		switch (strtoupper($environment)) {
			case strtoupper($this->getServerEnvironmentDevelopmentValue()):
				return Sr::ENV_DEVELOPMENT;
			case strtoupper($this->getServerEnvironmentProductionValue()):
				return Sr::ENV_PRODUCTION;
			case strtoupper($this->getServerEnvironmentTestingValue()):
				return Sr::ENV_TESTING;
			default:
				throw new Soter_Exception_500('wrong parameter value[' . $environment . '] of getServerEnvironment(), '
				. 'should be one of [' . $this->getServerEnvironmentDevelopmentValue() . ',' .
				$this->getServerEnvironmentTestingValue() . ',' .
				$this->getServerEnvironmentProductionValue() . ']');
		}
	}

	public function getServerEnvironmentTestingValue() {
		return $this->serverEnvironmentTestingValue;
	}

	public function getServerEnvironmentProductionValue() {
		return $this->serverEnvironmentProductionValue;
	}

	public function getServerEnvironmentDevelopmentValue() {
		return $this->serverEnvironmentDevelopmentValue;
	}

	public function setServerEnvironmentDevelopmentValue($serverEnvironmentDevelopmentValue) {
		$this->serverEnvironmentDevelopmentValue = $serverEnvironmentDevelopmentValue;
		return $this;
	}

	public function setServerEnvironmentTestingValue($serverEnvironmentTestingValue) {
		$this->serverEnvironmentTestingValue = $serverEnvironmentTestingValue;
		return $this;
	}

	public function setServerEnvironmentProductionValue($serverEnvironmentProductionValue) {
		$this->serverEnvironmentProductionValue = $serverEnvironmentProductionValue;
		return $this;
	}

	/**
	 * 获取当前运行环境下，配置文件目录路径
	 * @return type
	 */
	public function getConfigCurrentDirName() {
		$name = $this->getConfigDevelopmentDirName();
		switch ($this->environment) {
			case Sr::ENV_DEVELOPMENT :
				$name = $this->getConfigDevelopmentDirName();
				break;
			case Sr::ENV_TESTING :
				$name = $this->getConfigTestingDirName();
				break;
			case Sr::ENV_PRODUCTION :
				$name = $this->getConfigProductionDirName();
				break;
		}
		return $name;
	}

	public function getEnvironment() {
		return $this->environment;
	}

	public function setEnvironment($environment) {
		if (!in_array($environment, array(Sr::ENV_DEVELOPMENT, Sr::ENV_PRODUCTION, Sr::ENV_TESTING))) {
			throw new Soter_Exception_500('wrong parameter value[' . $environment . '] of setEnvironment(), should be one of [Sr::ENV_DEVELOPMENT,Sr::ENV_PRODUCTION,Sr::ENV_TESTING]');
		}
		$this->environment = $environment;
		return $this;
	}

	public function getConfigDirName() {
		return $this->configDirName;
	}

	public function getConfigTestingDirName() {
		return $this->configTestingDirName;
	}

	public function getConfigProductionDirName() {
		return $this->configProductionDirName;
	}

	public function getConfigDevelopmentDirName() {
		return $this->configDevelopmentDirName;
	}

	public function setConfigDirName($configDirName) {
		$this->configDirName = $configDirName;
		return $this;
	}

	public function setConfigTestingDirName($configTestingDirName) {
		$this->configTestingDirName = $configTestingDirName;
		return $this;
	}

	public function setConfigProductionDirName($configProductionDirName) {
		$this->configProductionDirName = $configProductionDirName;
		return $this;
	}

	public function setConfigDevelopmentDirName($configDevelopmentDirName) {
		$this->configDevelopmentDirName = $configDevelopmentDirName;
		return $this;
	}

	/**
	 * 
	 * @return Soter_Route
	 */
	public function getRoute() {
		return empty($this->route) ? new Soter_Route() : $this->route;
	}

	public function setRoute($route) {
		$this->route = $route;
		return $this;
	}

	public function getLibraryDirName() {
		return $this->libraryDirName;
	}

	public function setLibraryDirName($libraryDirName) {
		$this->libraryDirName = $libraryDirName;
		return $this;
	}

	public function getHmvcDirName() {
		return $this->hmvcDirName;
	}

	public function setHmvcDirName($hmvcDirName) {
		$this->hmvcDirName = $hmvcDirName;
		return $this;
	}

	public function getHmvcModules() {
		return $this->hmvcModules;
	}

	public function setHmvcModules($hmvcModules) {
		$this->hmvcModules = $hmvcModules;
		return $this;
	}

	public function getControllerDirName() {
		return $this->controllerDirName;
	}

	public function setControllerDirName($controllerDirName) {
		$this->controllerDirName = $controllerDirName;
		return $this;
	}

	public function getExceptionHandle() {
		return $this->exceptionHandle;
	}

	public function setExceptionHandle($exceptionHandle) {
		$this->exceptionHandle = $exceptionHandle;
		return $this;
	}

	public function getApplicationDir() {
		return $this->applicationDir;
	}

	public function getIndexDir() {
		return $this->indexDir;
	}

	public function getIndexName() {
		return $this->indexName;
	}

	public function getLoggerWriterContainer() {
		return $this->loggerWriterContainer;
	}

	public function setApplicationDir($applicationDir) {
		$this->applicationDir = Sr::realPath($applicationDir) . '/';
		if (empty($this->primaryApplicationDir)) {
			$this->primaryApplicationDir = $this->applicationDir;
		}
		return $this;
	}

	public function setIndexDir($indexDir) {
		$this->indexDir = Sr::realPath($indexDir) . '/';
		return $this;
	}

	public function setIndexName($indexName) {
		$this->indexName = $indexName;
		return $this;
	}

	public function setLoggerWriterContainer(Soter_Logger_Writer $loggerWriterContainer) {
		$this->loggerWriterContainer = $loggerWriterContainer;
		return $this;
	}

	public function getMethodPrefix() {
		return $this->methodPrefix;
	}

	public function getMethodUriSubfix() {
		return $this->methodUriSubfix;
	}

	public function setMethodPrefix($methodPrefix) {
		$this->methodPrefix = $methodPrefix;
		return $this;
	}

	public function setMethodUriSubfix($methodUriSubfix) {
		if (!$methodUriSubfix) {
			throw new Soter_Exception_500('"Method Uri Subfix" can not be empty.');
		}
		$this->methodUriSubfix = $methodUriSubfix;
		return $this;
	}

	public function getDefaultController() {
		return $this->defaultController;
	}

	public function getDefaultMethod() {
		return $this->defaultMethod;
	}

	public function setDefaultController($defaultController) {
		$this->defaultController = $defaultController;
		return $this;
	}

	public function setDefaultMethod($defaultMethod) {
		$this->defaultMethod = $defaultMethod;
		return $this;
	}

	public function getClassesDirName() {
		return $this->classesDirName;
	}

	public function setClassesDirName($classesDirName) {
		$this->classesDirName = $classesDirName;
		return $this;
	}

	public function getPackages() {
		return array_merge($this->packageMasterContainer, $this->packageContainer);
	}

	public function addMasterPackages(Array $packagesPath) {
		foreach ($packagesPath as $packagePath) {
			$this->addMasterPackage($packagePath);
		}
		return $this;
	}

	public function addMasterPackage($packagePath) {
		$packagePath = Sr::realPath($packagePath) . '/';
		if (!in_array($packagePath, $this->packageMasterContainer)) {
			//注册“包”到主包容器中
			array_push($this->packageMasterContainer, $packagePath);
			if (file_exists($library = $packagePath . $this->getLibraryDirName() . '/')) {
				array_push($this->packageMasterContainer, $library);
			}
		}
		return $this;
	}

	public function addPackages(Array $packagesPath) {
		foreach ($packagesPath as $packagePath) {
			$this->addPackage($packagePath);
		}
		return $this;
	}

	public function addPackage($packagePath) {
		$packagePath = Sr::realPath($packagePath) . '/';
		if (!in_array($packagePath, $this->packageContainer)) {
			//注册“包”到包容器中
			array_push($this->packageContainer, $packagePath);
			if (file_exists($library = $packagePath . $this->getLibraryDirName() . '/')) {
				array_push($this->packageContainer, $library);
			}
		}
		return $this;
	}

	/**
	 * 加载项目目录下的bootstrap.php配置
	 */
	public function bootstrap() {
		//引入“bootstrap”配置
		if (file_exists($bootstrap = $this->getApplicationDir() . 'bootstrap.php')) {
			Sr::includeOnce($bootstrap);
		}
	}

	public function getShowError() {
		return $this->showError;
	}

	public function getRoutersContainer() {
		return $this->routersContainer;
	}

	public function setShowError($showError) {
		$this->showError = $showError;
		return $this;
	}

	/**
	 * 
	 * @return Soter_Request
	 */
	public function getRequest() {
		return $this->request;
	}

	public function setRequest(Soter_Request $request) {
		$this->request = $request;
		return $this;
	}

	public function addRouter(Soter_Router $router) {
		array_unshift($this->routersContainer, $router);
		return $this;
	}

	public function getRouters() {
		return $this->routersContainer;
	}

	public function addLoggerWriter(Soter_Logger_Writer $loggerWriter) {
		$this->loggerWriterContainer[] = $loggerWriter;
		return $this;
	}

	public function getLoggerWriters() {
		return $this->loggerWriterContainer;
	}

	public function getIsRewrite() {
		return $this->isRewrite;
	}

	public function setTimeZone($timeZone) {
		date_default_timezone_set($timeZone);
		return $this;
	}

	public function setIsRewrite($isRewrite) {
		$this->isRewrite = $isRewrite;
		return $this;
	}

}

class Soter_Logger_Writer_Dispatcher {

	private static $instance;

	public static function initialize() {
		if (empty(self::$instance)) {
			self::$instance = new self();
			error_reporting(E_ALL);
			//只在web和命令行模式下关闭错误显示，插件模式不应该关闭
			if (!Sr::isPluginMode()) {
				ini_set('display_errors', FALSE);
			}
			set_exception_handler(array(self::$instance, 'handleException'));
			set_error_handler(array(self::$instance, 'handleError'));
			register_shutdown_function(array(self::$instance, 'handleFatal'));
		}
	}

	final public function handleException(Exception $exception) {

		if (is_subclass_of($exception, 'Soter_Exception')) {
			$this->dispatch($exception);
		} else {
			$this->dispatch(new Soter_Exception_500($exception->getMessage(), $exception->getCode(), get_class($exception), $exception->getFile(), $exception->getLine()));
		}
	}

	final public function handleError($code, $message, $file, $line) {
		if (0 == error_reporting()) {
			return;
		}
		$this->dispatch(new Soter_Exception_500($message, $code, 'General Error', $file, $line));
	}

	final public function handleFatal() {
		if (0 == error_reporting()) {
			return;
		}
		$lastError = error_get_last();
		$fatalError = array(1, 256, 64, 16, 4, 4096);
		if (!isset($lastError["type"]) || !in_array($lastError["type"], $fatalError)) {
			return;
		}
		$this->dispatch(new Soter_Exception_500($lastError['message'], $lastError['type'], 'Fatal Error', $lastError['file'], $lastError['line']));
	}

	final public function dispatch(Soter_Exception $exception) {
		$config = Sr::config();
		ini_set('display_errors', TRUE);
		$loggerWriters = $config->getLoggerWriters();
		foreach ($loggerWriters as $loggerWriter) {
			$loggerWriter->write($exception);
		}
		if ($config->getShowError()) {
			$handle = $config->getExceptionHandle();
			if ($handle instanceof Soter_Exception_Handle) {
				$handle->handle($exception);
			} else {
				$exception->render();
			}
		}
		exit();
	}

}

class Soter_Logger_FileWriter implements Soter_Logger_Writer {

	private $logsDirPath;

	public function __construct($logsDirPath) {
		$this->logsDirPath = Sr::realPath($logsDirPath) . '/' . date(Sr::config()->getLogsSubDirNameFormat()) . '/';
	}

	public function write(Soter_Exception $exception) {
		$content = 'Domain : ' . Sr::server('http_host') . "\n"
			. 'ClientIP : ' . Sr::server('SERVER_ADDR') . "\n"
			. 'ServerIP : ' . Sr::serverIp() . "\n"
			. 'ServerHostname : ' . Sr::hostname() . "\n"
			. (!Sr::isCli() ? 'Request Uri : ' . Sr::server('request_uri') : '') . "\n"
			. (!Sr::isCli() ? 'Get Data : ' . json_encode(Sr::get()) : '') . "\n"
			. (!Sr::isCli() ? 'Post Data : ' . json_encode(Sr::post()) : '') . "\n"
			. (!Sr::isCli() ? 'Cookie Data : ' . json_encode(Sr::cookie()) : '') . "\n"
			. (!Sr::isCli() ? 'Server Data : ' . json_encode(Sr::server()) : '') . "\n"
			. $exception->renderCli() . "\n";
		if (!is_dir($this->logsDirPath)) {
			mkdir($this->logsDirPath, 0700, true);
		}
		if (!file_exists($logsFilePath = $this->logsDirPath . 'logs.php')) {
			$content = '<?php defined("IN_SOTER") or exit();?>' . "\n" . $content;
		}
		file_put_contents($logsFilePath, $content, LOCK_EX | FILE_APPEND);
	}

}

class Soter_Maintain_Handle_Default implements Soter_Maintain_Handle {

	public function handle() {
		header('Content-type: text/html;charset=utf-8');
		echo '<center><h2>server is under maintenance</h2><h3>服务器维护中</h3>' . date('Y/m/d H:i:s e') . '</center>';
	}

}

class Soter_Uri_Rewriter_Default implements Soter_Uri_Rewriter {

	public function rewrite($uri) {
		return $uri;
	}

}

class Soter_Exception_Handle_Default implements Soter_Exception_Handle {

	public function handle(Soter_Exception $exception) {
		$exception->render();
	}

}

class Soter_Database_SlowQuery_Handle_Default implements Soter_Database_SlowQuery_Handle {

	public function handle($sql, $explainString, $time) {
		$dir = Sr::config()->getPrimaryApplicationDir() . 'storage/slow-query-debug/';
		$file = $dir . 'slow-query-debug.php';
		if (!is_dir($dir)) {
			mkdir($dir, 0700, true);
		}
		$content = "\nSQL : " . $sql
			. "\nExplain : " . $explainString
			. "\nUsingTime : " . $time . " ms"
			. "\nTime : " . date('Y-m-d H:i:s') . "\n";
		if (!file_exists($file)) {
			$content = '<?php defined("IN_SOTER") or exit();?>' . "\n" . $content;
		}
		file_put_contents($file, $content, LOCK_EX | FILE_APPEND);
	}

}

class Soter_Database_Index_Handle_Default implements Soter_Database_Index_Handle {

	public function handle($sql, $explainString, $time) {
		$dir = Sr::config()->getPrimaryApplicationDir() . 'storage/index-debug/';
		$file = $dir . 'index-debug.php';
		if (!is_dir($dir)) {
			mkdir($dir, 0700, true);
		}
		$content = "\nSQL : " . $sql
			. "\nExplain : " . $explainString
			. "\nUsingTime : " . $time . " ms"
			. "\nTime : " . date('Y-m-d H:i:s') . "\n";
		if (!file_exists($file)) {
			$content = '<?php defined("IN_SOTER") or exit();?>' . "\n" . $content;
		}
		file_put_contents($file, $content, LOCK_EX | FILE_APPEND);
	}

}

class Soter_Cache_File implements Soter_Cache {

	private $_cacheDirPath;

	public function __construct($cacheFileName = '') {
		$cacheDirPath = empty($cacheFileName) ? Sr::config()->getPrimaryApplicationDir() . 'storage/cache/' : Sr::config($cacheFileName);
		$this->_cacheDirPath = Sr::realPath($cacheDirPath) . '/';
		if (!is_dir($this->_cacheDirPath)) {
			mkdir($this->_cacheDirPath, 0700, true);
		}
		if (!is_writable($this->_cacheDirPath)) {
			throw new Soter_Exception_500('cache dir [ ' . Sr::safePath($this->_cacheDirPath) . ' ] not writable');
		}
	}

	private function _hashKey($key) {
		return md5($key);
	}

	private function _hashKeyPath($key) {
		$key = md5($key);
		$len = strlen($key);
		return $this->_cacheDirPath . $key{$len - 1} . '/' . $key{$len - 2} . '/' . $key{$len - 3} . '/';
	}

	private function pack($userData, $cacheTime) {
		$cacheTime = (int) $cacheTime;
		return @serialize(array(
			    'userData' => $userData,
			    'expireTime' => ($cacheTime == 0 ? 0 : time() + $cacheTime)
		));
	}

	private function unpack($cacheData) {
		$cacheData = @unserialize($cacheData);
		if (is_array($cacheData) && isset($cacheData['userData']) && isset($cacheData['expireTime'])) {
			if ($cacheData['expireTime'] == 0) {
				return $cacheData['userData'];
			}
			return $cacheData['expireTime'] > time() ? $cacheData['userData'] : NULL;
		} else {
			return NULL;
		}
	}

	public function clean() {
		return Sr::rmdir($this->_cacheDirPath, false);
	}

	public function delete($key) {
		if (empty($key)) {
			return false;
		}
		$key = $this->_hashKey($key);
		$filePath = $this->_hashKeyPath($key) . $key;
		if (file_exists($filePath)) {
			return @unlink($filePath);
		}
		return true;
	}

	/**
	 * 成功返回数据，失败返回null
	 * @param type $key
	 * @return type
	 */
	public function get($key) {
		if (empty($key)) {
			return null;
		}
		$key = $this->_hashKey($key);
		$filePath = $this->_hashKeyPath($key) . $key;
		if (file_exists($filePath)) {
			$cacheData = file_get_contents($filePath);
			$userData = $this->unpack($cacheData);
			return is_null($userData) ? null : $userData;
		}
		return NULL;
	}

	/**
	 * 成功返回true，失败返回false
	 * @param type $key       缓存key
	 * @param type $value     缓存数据
	 * @param type $cacheTime 缓存时间，单位秒
	 * @return boolean
	 */
	public function set($key, $value, $cacheTime) {
		if (empty($key)) {
			return false;
		}
		$key = $this->_hashKey($key);
		$cacheDir = $this->_hashKeyPath($key);
		$filePath = $cacheDir . $key;
		if (!is_dir($cacheDir)) {
			mkdir($cacheDir, 0700, true);
		}
		$cacheData = $this->pack($value, $cacheTime);
		if (empty($cacheData)) {
			return false;
		}
		return file_put_contents($filePath, $cacheData, LOCK_EX);
	}

}

class Soter_Cache_Memcached implements Soter_Cache {

	private $config, $handle;

	public function __construct($configFileName) {
		if (is_array($configFileName)) {
			$this->config = $configFileName;
		} else {
			$this->config = Sr::config($configFileName);
		}
	}

	private function _init() {
		if (empty($this->handle)) {
			$this->handle = new Memcached();
			foreach ($this->config as $server) {
				if ($server[2] > 0) {
					$this->handle->addServer($server[0], $server[1], $server[2]);
				} else {
					$this->handle->addServer($server[0], $server[1]);
				}
			}
		}
	}

	public function clean() {
		$this->_init();
		$this->handle->flush();
	}

	public function delete($key) {
		$this->_init();
		$this->handle->delete($key);
	}

	public function get($key) {
		$this->_init();
		return ($data = $this->handle->get($key)) ? $data : null;
	}

	public function set($key, $value, $cacheTime) {
		$this->_init();
		return $this->handle->set($key, $value, $cacheTime > 0 ? (time() + $cacheTime) : 0);
	}

}

class Soter_Cache_Memcache implements Soter_Cache {

	private $config, $handle;

	public function __construct($configFileName) {
		if (is_array($configFileName)) {
			$this->config = $configFileName;
		} else {
			$this->config = Sr::config($configFileName);
		}
	}

	private function _init() {
		if (empty($this->handle)) {
			$this->handle = new Memcache();
			foreach ($this->config as $server) {
				$this->handle->addserver($server[0], $server[1]);
			}
		}
	}

	public function clean() {
		$this->_init();
		return $this->handle->flush();
	}

	public function delete($key) {
		$this->_init();
		return $this->handle->delete($key);
	}

	public function get($key) {
		$this->_init();
		return ($data = $this->handle->get($key)) ? $data : null;
	}

	public function set($key, $value, $cacheTime) {
		$this->_init();
		return $this->handle->set($key, $value, false, $cacheTime);
	}

}

class Soter_Cache_Apc implements Soter_Cache {

	public function clean() {
		@apc_clear_cache();
		@apc_clear_cache("user");
	}

	public function delete($key) {
		return apc_delete($key);
	}

	public function get($key) {
		$data = apc_fetch($key, $bo);
		if ($bo === false) {
			return null;
		}
		return $data;
	}

	public function set($key, $value, $cacheTime) {
		return apc_store($key, $value, $cacheTime);
	}

}

class Soter_Cache_Redis implements Soter_Cache {

	private $config, $handle;

	private function _init() {
		if (empty($this->handle)) {
			$this->handle = array();
			foreach (array('masters', 'slaves') as $type) {
				foreach ($this->config[$type] as $k => $config) {
					$this->handle[$type][$k] = new Redis();
					if ($config['type'] == 'sock') {
						$this->handle[$type][$k]->connect($config['sock']);
					} else {
						$this->handle[$type][$k]->connect($config['host'], $config['port'], $config['timeout'], $config['retry']);
					}
					if (!is_null($config['password'])) {
						$this->handle[$type][$k]->auth($config['password']);
					}
					if (!is_null($config['prefix'])) {
						if ($config['prefix']{strlen($config['prefix']) - 1} != ':') {
							$config['prefix'].=':';
						}
						$this->handle[$type][$k]->setOption(Redis::OPT_PREFIX, $config['prefix']);
					}
				}
			}
			if (empty($this->handle['slaves']) && !empty($this->handle['masters'])) {
				$this->handle['slaves'] = array();
				$this->handle['slaves'][0] = &$this->handle['masters'][key($this->handle['masters'])];
			}
		}
	}

	public function __construct($configFileName) {
		if (is_array($configFileName)) {
			$this->config = $configFileName;
		} else {
			$this->config = Sr::config($configFileName);
		}
	}

	public function clean() {
		$this->_init();
		$status = true;
		foreach ($this->handle['masters'] as &$handle) {
			$status = $status & $handle->flushDB();
		}
		return $status;
	}

	public function delete($key) {
		$this->_init();
		$status = true;
		foreach ($this->handle['masters'] as &$handle) {
			$status = $status & $handle->delete($key);
		}
		return $status;
	}

	public function get($key) {
		$this->_init();
		$k = array_rand($this->handle['slaves']);
		$handle = &$this->handle['slaves'][$k];
		if ($data = $handle->get($key)) {
			return @unserialize($data);
		} else {
			return null;
		}
	}

	public function set($key, $value, $cacheTime) {
		$this->_init();
		$value = serialize($value);
		foreach ($this->handle['masters'] as &$handle) {
			if ($cacheTime) {
				return $handle->setex($key, $cacheTime, $value);
			} else {
				return $handle->set($key, $value);
			}
		}
	}

}

class Soter_Generator extends Soter_Task {

	public function execute(Soter_CliArgs $args) {
		$config = Sr::config();
		$name = $args->get('name');
		$type = $args->get('type');
		$force = $args->get('overwrite');
		if (empty($name)) {
			exit('name required , please use : --name=<Name>');
		}
		if (empty($type)) {
			exit('type required , please use : --type=<Type>');
		}
		$classesDir = $config->getPrimaryApplicationDir() . $config->getClassesDirName() . '/';
		$info = array(
		    'controller' => array(
			'dir' => $config->getControllerDirName(),
			'parentClass' => 'Soter_Controller',
			'methodName' => Sr::config()->getMethodPrefix() . 'index()',
			'nameTip' => 'Controller'
		    ),
		    'business' => array(
			'dir' => $config->getBusinessDirName(),
			'parentClass' => 'Soter_Business',
			'methodName' => 'business()',
			'nameTip' => 'Business'
		    ),
		    'model' => array(
			'dir' => $config->getModelDirName(),
			'parentClass' => 'Soter_Model',
			'methodName' => 'model()',
			'nameTip' => 'Model'
		    ),
		    'task' => array(
			'dir' => $config->getTaskDirName(),
			'parentClass' => 'Soter_Task',
			'methodName' => 'execute(Soter_CliArgs $args)',
			'nameTip' => 'Task'
		    )
		);
		if (!isset($info[$type])) {
			exit('[ Error ]' . "\n" . 'Type : [ ' . $type . ' ]');
		}
		$classname = $info[$type]['dir'] . '_' . $name;
		$file = $classesDir . str_replace('_', '/', $classname) . '.php';
		$method = $info[$type]['methodName'];
		$parentClass = $info[$type]['parentClass'];
		$tip = $info[$type]['nameTip'];
		if (file_exists($file)) {
			if ($force) {
				$this->writeFile($classname, $method, $parentClass, $file, $tip);
			} else {
				exit('[ Error ]' . "\n" . $tip . ' [ ' . $classname . ' ] already exists , ' . "{$file}\n" . 'you can use --overwrite to overwrite the file.');
			}
		} else {
			$this->writeFile($classname, $method, $parentClass, $file, $tip);
		}
	}

	private function writeFile($classname, $method, $parentClass, $file, $tip) {
		$dir = dirname($file);
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		$code = "<?php\nclass  {$classname} extends {$parentClass} {\n	public function {$method} {\n		\n	}\n}";
		if (file_put_contents($file, $code)) {
			echo "[ Successfull ]\n{$tip} [ $classname ] created successfully \n" . $file;
		}
	}

}

class Soter_Generator_Mysql extends Soter_Task {

	public function execute(Soter_CliArgs $args) {
		$config = Sr::config();
		$name = $args->get('name');
		$type = $args->get('type');
		$force = $args->get('overwrite');
		$table = $args->get('table');
		$dbGroup = $args->get('db');
		if (empty($name)) {
			exit('name required , please use : --name=<Name>');
		}
		if (empty($table)) {
			exit('table name required , please use : --table=<Table Name>');
		}
		if (empty($type)) {
			exit('type required , please use : --type=<Type>');
		}
		$columns = self::getTableFieldsInfo($table, $dbGroup);
		$primaryKey = '';

		$classesDir = $config->getPrimaryApplicationDir() . $config->getClassesDirName() . '/';
		$info = array(
		    'bean' => array(
			'dir' => $config->getBeanDirName(),
			'parentClass' => 'Soter_Bean',
			'nameTip' => 'Bean'
		    ),
		    'dao' => array(
			'dir' => $config->getDaoDirName(),
			'parentClass' => 'Soter_Dao',
			'nameTip' => 'Dao'
		    ),
		);
		if (!isset($info[$type])) {
			exit('[ Error ]' . "\n" . 'Type : [ ' . $type . ' ]');
		}
		$classname = $info[$type]['dir'] . '_' . $name;
		$file = $classesDir . str_replace('_', '/', $classname) . '.php';
		$parentClass = $info[$type]['parentClass'];
		$tip = $info[$type]['nameTip'];
		$dir = dirname($file);
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		if ($type == 'bean') {
			$methods = array();
			$fields = array();
			$fieldTemplate = "	//{comment}\n	private \${column0};";
			$methodTemplate = "	public function get{column}() {\n		return \$this->{column0};\n	}\n\n	public function set{column}(\${column0}) {\n		\$this->{column0} = \${column0};\n		return \$this;\n	}";
			foreach ($columns as $value) {
				$column = ucfirst($value['name']);
				$column0 = $value['name'];
				$fields[] = str_replace(array('{column0}', '{comment}'), array($column0, $value['comment']), $fieldTemplate);
				$methods[] = str_replace(array('{column}', '{column0}'), array($column, $column0), $methodTemplate);
			}
			$code = "<?php\n\nclass {$classname} extends {$parentClass} {\n\n{fields}\n\n{methods}\n\n}";
			$code = str_replace(array('{fields}', '{methods}'), array(implode("\n\n", $fields), implode("\n\n", $methods)), $code);
		} else {
			$columnsString = '';
			$_columns = array();
			foreach ($columns as $value) {
				if ($value['primary']) {
					$primaryKey = $value['name'];
				}
				$_columns[] = '\'' . $value['name'] . "'//" . $value['comment'] . "\n				";
			}
			$columnsString = "array(\n				" . implode(',', $_columns) . ')';
			$code = "<?php\n\nclass {$classname} extends {$parentClass} {\n\n	public function getColumns() {\n		return {columns};\n	}\n\n	public function getPrimaryKey() {\n		return '{primaryKey}';\n	}\n\n	public function getTable() {\n		return '{table}';\n	}\n\n}\n";
			$code = str_replace(array('{columns}', '{primaryKey}', '{table}'), array($columnsString, $primaryKey, $table), $code);
		}
		if (file_exists($file)) {
			if ($force) {
				if (file_put_contents($file, $code)) {
					echo "[ Successfull ]\n{$tip} [ $classname ] created successfully \n" . $file;
				}
			} else {
				exit('[ Error ]' . "\n" . $tip . ' [ ' . $classname . ' ] already exists , ' . "{$file}\n" . 'you can use --overwrite to overwrite the file.');
			}
		} else {
			if (file_put_contents($file, $code)) {
				echo "[ Successfull ]\n{$tip} [ $classname ] created successfully \n" . $file;
			}
		}
	}

	/**
	 * 获取表字段信息，并返回
	 * 提示：
	 * 只适用于mysql数据库
	 * @param type $tableName   不含前缀的表名称
	 * @param type $db           数据库组配置名称，或者数据库对象，或者数据库配置数组
	 * @return array $info
	 */
	public static function getTableFieldsInfo($tableName, $db) {
		if (!is_object($db)) {
			$db = Sr::db($db);
		}
		if ($db->getDriverType() != 'mysql') {
			throw new Soter_Exception_500('getTableFieldsInfo() only for mysql database');
		}
		$info = array();
		$result = $db->execute('SHOW FULL COLUMNS FROM ' . $db->getTablePrefix() . $tableName)->rows();
		if ($result) {
			foreach ($result as $val) {
				$info[$val['Field']] = array(
				    'name' => $val['Field'],
				    'type' => $val['Type'],
				    'comment' => $val['Comment'] ? $val['Comment'] : $val['Field'],
				    'notnull' => $val['Null'] == 'NO' ? 1 : 0,
				    'default' => $val['Default'],
				    'primary' => (strtolower($val['Key']) == 'pri'),
				    'autoinc' => (strtolower($val['Extra']) == 'auto_increment'),
				);
			}
		}
		return $info;
	}

}

class Soter_Session_Redis extends Soter_Session {

	public function init() {
		ini_set('session.save_handler', 'redis');
		ini_set('session.save_path', $this->config['path']);
	}

}

class Soter_Session_Memcached extends Soter_Session {

	public function init() {
		ini_set('session.save_handler', 'memcached');
		ini_set('session.save_path', $this->config['path']);
	}

}

class Soter_Session_Memcache extends Soter_Session {

	public function init() {
		ini_set('session.save_handler', 'memcache');
		ini_set('session.save_path', $this->config['path']);
	}

}

class Soter_Session_Mongodb extends Soter_Session {

	private $__mongo_collection = NULL;
	private $__current_session = NULL;
	private $__mongo_conn = NULL;

	public function __construct($configFileName) {
		parent::__construct($configFileName);
		$cfg = Sr::config()->getSessionConfig();
		$this->config['lifetime'] = $cfg['lifetime'];
	}

	public function connect() {
		if (is_object($this->__mongo_collection)) {
			return;
		}
		$connection_string = sprintf('mongodb://%s:%s', $this->config['host'], $this->config['port']);
		if ($this->config['user'] != null && $this->config['password'] != null) {
			$connection_string = sprintf('mongodb://%s:%s@%s:%s/%s', $this->config['user'], $this->config['password'], $this->config['host'], $this->config['port'], $this->config['database']);
		}
		$opts = array('connect' => true);
		if ($this->config['persistent'] && !empty($this->config['persistentId'])) {
			$opts['persist'] = $this->config['persistentId'];
		}
		if ($this->config['replicaSet']) {
			$opts['replicaSet'] = $this->config['replicaSet'];
		}
		$class = 'MongoClient';
		if (!class_exists($class)) {
			$class = 'Mongo';
		}
		$this->__mongo_conn = $object_conn = new $class($connection_string, $opts);
		$object_mongo = $object_conn->{$this->config['database']};
		$this->__mongo_collection = $object_mongo->{$this->config['collection']};
		if ($this->__mongo_collection == NULL) {
			throw new Soter_Exception_500('can not connect to mongodb server');
		}
	}

	public function init() {
		session_set_save_handler(array(&$this, 'open'), array(&$this, 'close'), array(&$this, 'read'), array(&$this, 'write'), array(&$this, 'destroy'), array(&$this, 'gc'));
	}

	public function open($session_path, $session_name) {
		$this->connect();
		return true;
	}

	public function close() {
		$this->__mongo_conn->close();
		return true;
	}

	public function read($session_id) {
		$result = NULL;
		$ret = '';
		$expiry = time();
		$query['_id'] = $session_id;
		$query['expiry'] = array('$gte' => $expiry);
		$result = $this->__mongo_collection->findone($query);
		if ($result) {
			$this->__current_session = $result;
			$result['expiry'] = time() + $this->config['lifetime'];
			$this->__mongo_collection->update(array("_id" => $session_id), $result);
			$ret = $result['data'];
		}
		return $ret;
	}

	public function write($session_id, $data) {
		$result = true;
		$expiry = time() + $this->config['lifetime'];
		$session_data = array();
		if (empty($this->__current_session)) {
			$session_id = $session_id;
			$session_data['_id'] = $session_id;
			$session_data['data'] = $data;
			$session_data['expiry'] = $expiry;
		} else {
			$session_data = (array) $this->__current_session;
			$session_data['data'] = $data;
			$session_data['expiry'] = $expiry;
		}
		$query['_id'] = $session_id;
		$record = $this->__mongo_collection->findOne($query);
		if ($record == null) {
			$this->__mongo_collection->insert($session_data);
		} else {
			$record['data'] = $data;
			$record['expiry'] = $expiry;
			$this->__mongo_collection->save($record);
		}
		return true;
	}

	public function destroy($session_id) {
		unset($_SESSION);
		$query['_id'] = $session_id;
		$this->__mongo_collection->remove($query);
		return true;
	}

	public function gc($max = 0) {
		$query = array();
		$query['expiry'] = array(':lt' => time());
		$this->__mongo_collection->remove($query, array('justOne' => false));
		return true;
	}

}

/**
 * @property Soter_Database_ActiveRecord $dbConnection Description
 */
class Soter_Session_Mysql extends Soter_Session {

	protected $dbConnection;
	protected $dbTable;

	public function __construct($configFileName) {
		parent::__construct($configFileName);
		$cfg = Sr::config()->getSessionConfig();
		$this->config['lifetime'] = $cfg['lifetime'];
	}

	public function init() {
		session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
	}

	public function connect() {
		$this->dbTable = $this->config['table'];
		if ($this->config['group']) {
			$this->dbConnection = Sr::db($this->config['group']);
		} else {
			$dbConfig = Soter_Database::getDefaultConfig();
			$dbConfig['database'] = $this->config['database'];
			$dbConfig['tablePrefix'] = $this->config['table_prefix'];
			$dbConfig['masters']['master01']['hostname'] = $this->config['hostname'];
			$dbConfig['masters']['master01']['port'] = $this->config['port'];
			$dbConfig['masters']['master01']['username'] = $this->config['username'];
			$dbConfig['masters']['master01']['password'] = $this->config['password'];
			$this->dbConnection = Sr::db($dbConfig);
		}
	}

	public function open($save_path, $session_name) {
		if (!is_object($this->dbConnection)) {
			$this->connect();
		}
		return TRUE;
	}

	public function close() {
		return $this->dbConnection->close();
	}

	public function read($id) {
		$result = $this->dbConnection->from($this->dbTable)->where(array('id' => $id))->execute();
		if ($result->total()) {
			$record = $result->row();
			$where['id'] = $record['id'];
			$data['timestamp'] = time() + intval($this->config['lifetime']);
			$this->dbConnection->update($this->dbTable, $data, $where)->execute();
			return $record['data'];
		} else {
			return false;
		}
		return true;
	}

	public function write($id, $sessionData) {
		$data['id'] = $id;
		$data['data'] = $sessionData;
		$data['timestamp'] = time() + intval($this->config['lifetime']);
		$this->dbConnection->replace($this->dbTable, $data);
		return $this->dbConnection->execute();
	}

	public function destroy($id) {
		unset($_SESSION);
		return $this->dbConnection->delete($this->dbTable, array('id' => $id))->execute();
	}

	public function gc($max = 0) {
		return $this->dbConnection->delete($this->dbTable, array('timestamp <' => time()))->execute();
	}

}
