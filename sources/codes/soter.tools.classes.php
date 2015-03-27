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

class Soter_Response {
	
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

	public function setArgs($args) {
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
		$hmvcMethodName = Sr::arrayGet($get, $config->getRouterUrlMethodKey(), '');
		$hmvcModuleName = Sr::arrayGet($get, $config->getRouterUrlModuleKey(), '');
		//hmvc检测
		$hmvcModuleDirName = Soter::checkHmvc($hmvcModuleName, false);
		if ($controllerName) {
			$controllerName = $config->getControllerDirName() . '_' . $controllerName;
		}
		if ($hmvcMethodName) {
			$hmvcMethodName = $config->getMethodPrefix() . $hmvcMethodName;
		}
		return $this->route->setHmvcModuleName($hmvcModuleName)
				->setController($controllerName)
				->setMethod($hmvcMethodName)
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
		//获取uri
		$uri = $config->getRequest()->getUri();

		/**
		 * pathinfo模式路由判断以及解析uri中的访问路径 
		 * 比如：http://127.0.0.1/index.php/Welcome/index.do?id=11
		 * 获取的是后面的(Welcome/index.do)部分，也就是index.php/和?之间的部分
		 */
		$indexName = Soter::getConfig()->getIndexName();
		if (($pos = stripos($uri, '/' . $indexName)) !== FALSE) {
			$uri = ltrim(substr($uri, $pos + strlen('/' . $indexName)), '/');
			$_uriarr = explode('?', $uri);
			$uri = trim(current($_uriarr), '/');
			if ($uriRewriter = $config->getUriRewriter()) {
				$uri = $uriRewriter->rewrite($uri);
			}
		} else {
			$uri = '';
		}
		if (empty($uri)) {
			//没有找到hmvc模块名称，或者控制器名称
			return $this->route->setFound(FALSE);
		}
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
				->setHmvcModuleName($hmvcModule)
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
		$timeZone = 'PRC',
		$classesDirName = 'classes',
		$hmvcDirName = 'hmvc',
		$libraryDirName = 'library',
		$functionsDirName = 'functions',
		$configDirName = 'config',
		$configTestingDirName = 'testing',
		$configProductionDirName = 'production',
		$configDevelopmentDirName = 'development',
		$controllerDirName = 'Controller',
		$businessDirName = 'Business',
		$daoDirName = 'Dao',
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
		$excptionErrorJsonMessageName = 'errorMessage',
		$excptionErrorJsonFileName = 'errorFile',
		$excptionErrorJsonLineName = 'errorLine',
		$excptionErrorJsonTypeName = 'errorType',
		$excptionErrorJsonCodeName = 'errorCode',
		$excptionErrorJsonTraceName = 'errorTrace',
		$excptionErrorJsonTimeName = 'errorTime',
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
		$databseConfigFileName,
		$databseConfig,
		$cacheHandle

	;

	/**
	 * 
	 * @return Soter_Cache
	 */
	public function getCacheHandle() {
		return $this->cacheHandle;
	}

	public function setCacheHandle(Soter_Cache $cacheHandle) {
		$this->cacheHandle = $cacheHandle;
		return $this;
	}

	public function getDatabseConfig($group = null) {
		if (!is_array($this->databseConfig)) {
			$config = Sr::config($this->databseConfigFileName);
			$this->databseConfig = is_array($config) ? $config : array();
		}
		if (is_null($group)) {
			return $this->databseConfig;
		} else {
			return isset($this->databseConfig[$group]) ? $this->databseConfig[$group] : array();
		}
	}

	public function setDatabseConfigFile($databseConfigFileName) {
		$this->databseConfigFileName = $databseConfigFileName;
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
		$this->primaryApplicationDir = $primaryApplicationDir;
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
	public function &getRoute() {
		return $this->route;
	}

	public function setRoute(&$route) {
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

	public function getExcptionErrorJsonMessageName() {
		return $this->excptionErrorJsonMessageName;
	}

	public function getExcptionErrorJsonFileName() {
		return $this->excptionErrorJsonFileName;
	}

	public function getExcptionErrorJsonLineName() {
		return $this->excptionErrorJsonLineName;
	}

	public function getExcptionErrorJsonTypeName() {
		return $this->excptionErrorJsonTypeName;
	}

	public function getExcptionErrorJsonCodeName() {
		return $this->excptionErrorJsonCodeName;
	}

	public function getExcptionErrorJsonTraceName() {
		return $this->excptionErrorJsonTraceName;
	}

	public function getExcptionErrorJsonTimeName() {
		return $this->excptionErrorJsonTimeName;
	}

	public function setExcptionErrorJsonTimeName($excptionErrorJsonTimeName) {
		$this->excptionErrorJsonTimeName = $excptionErrorJsonTimeName;
		return $this;
	}

	public function setExcptionErrorJsonMessageName($excptionErrorJsonMessageName) {
		$this->excptionErrorJsonMessageName = $excptionErrorJsonMessageName;
		return $this;
	}

	public function setExcptionErrorJsonFileName($excptionErrorJsonFileName) {
		$this->excptionErrorJsonFileName = $excptionErrorJsonFileName;
		return $this;
	}

	public function setExcptionErrorJsonLineName($excptionErrorJsonLineName) {
		$this->excptionErrorJsonLineName = $excptionErrorJsonLineName;
		return $this;
	}

	public function setExcptionErrorJsonTypeName($excptionErrorJsonTypeName) {
		$this->excptionErrorJsonTypeName = $excptionErrorJsonTypeName;
		return $this;
	}

	public function setExcptionErrorJsonCodeName($excptionErrorJsonCodeName) {
		$this->excptionErrorJsonCodeName = $excptionErrorJsonCodeName;
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

	public function getTimeZone() {
		return $this->timeZone;
	}

	public function getIsRewrite() {
		return $this->isRewrite;
	}

	public function setTimeZone($timeZone) {
		$this->timeZone = $timeZone;
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

	public function __construct($cacheDirPath) {
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
		Sr::rmdir($this->_cacheDirPath, false);
	}

	public function delete($key) {
		if (empty($key)) {
			return;
		}
		$key = $this->_hashKey($key);
		$filePath = $this->_hashKeyPath($key) . $key;
		if (file_exists($filePath)) {
			@unlink($filePath);
		}
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

class Soter_Data_Checker {

	/**
	 * $source_data和$map的key一致，$map的value是返回数据的key
	 * 根据$map的key读取$source_data中的数据，结果是以map的value为key的数数组
	 * 
	 * @param Array $map 字段映射数组,格式：array('表单name名称'=>'表字段名称',...)
	 */
	public static function readData(Array $map, $source_data = null) {
		$data = array();
		$formdata = is_null($source_data) ? Sr::post() : $source_data;
		foreach ($formdata as $form_key => $val) {
			if (isset($map[$form_key])) {
				$data[$map[$form_key]] = $val;
			}
		}
		return $data;
	}

	public static function checkData(Array $rule, Array $data = NULL, &$return_data = NULL, $db = null) {
		if (is_null($data)) {
			$data = Sr::post();
		}
		$return_data = $data;
		/**
		 * 验证前默认值规则处理
		 */
		foreach ($rule as $col => $val) {
			//提取出默认值
			foreach ($val as $_rule => $msg) {
				if (stripos($_rule, 'default[') === 0) {
					//删除默认值规则
					unset($rule[$col][$_rule]);
					$matches = self::getCheckRuleInfo($_rule);
					$_r = $matches[1];
					$args = $matches[2];
					$return_data[$col] = isset($args[0]) ? $args[0] : '';
				}
			}
		}
		/**
		 * 验证前默认值规则处理,没有默认值就补空
		 * 并标记最后要清理的key
		 */
		$unset_keys = array();
		foreach ($rule as $col => $val) {
			if (!isset($return_data[$col])) {
				$return_data[$col] = '';
				$unset_keys[] = $col;
			}
		}
		/**
		 * 验证前set处理
		 */
		self::checkSetData('set', $rule, $return_data);
		/**
		 * 验证规则
		 */
		foreach ($rule as $col => $val) {
			foreach ($val as $_rule => $msg) {
				if (!empty($_rule)) {
					/**
					 * 可以为空规则检测
					 */
					if (empty($return_data[$col]) && isset($val['optional'])) {
						//当前字段，验证通过
						break;
					} else {
						$matches = self::getCheckRuleInfo($_rule);
						$_r = $matches[1];
						$args = $matches[2];
						if ($_r == 'set' || $_r == 'set_post' || $_r == 'optional') {
							continue;
						}
						if (!self::checkRule($_rule, $return_data[$col], $return_data, $db)) {
							/**
							 * 清理没有传递的key
							 */
							foreach ($unset_keys as $key) {
								unset($return_data[$key]);
							}
							return $msg;
						}
					}
				}
			}
		}
		/**
		 * 验证后set_post处理
		 */
		self::checkSetData('set_post', $rule, $return_data);

		/**
		 * 清理没有传递的key
		 */
		foreach ($unset_keys as $key) {
			unset($return_data[$key]);
		}
		return NULL;
	}

	private static function checkSetData($type, Array $rule, &$return_data = NULL) {
		foreach ($rule as $col => $val) {
			foreach (array_keys($val) as $_rule) {
				if (!empty($_rule)) {
					#有规则而且不是非必须的，但是没有数据，就补上空数据，然后进行验证
					if (!isset($return_data[$col])) {
						if (isset($_rule['optional'])) {
							break;
						} else {
							$return_data[$col] = '';
						}
					}
					$matches = self::getCheckRuleInfo($_rule);
					$_r = $matches[1];
					$args = $matches[2];
					if ($_r == $type) {
						$_v = $return_data[$col];
						$_args = array($_v, $return_data);
						foreach ($args as $func) {
							if (function_exists($func)) {
								$reflection = new ReflectionFunction($func);
								//如果是系统函数
								if ($reflection->isInternal()) {
									$_args = array($_v);
								}
							}
							$_v = self::call($func, $_args);
						}
						$return_data[$col] = $_v;
					}
				}
			}
		}
	}

	private static function getCheckRuleInfo($_rule) {
		$matches = array();
		preg_match('|([^\[]+)(?:\[(.*)\](.?))?|', $_rule, $matches);
		$matches[1] = isset($matches[1]) ? $matches[1] : '';
		$matches[3] = !empty($matches[3]) ? $matches[3] : ',';
		if ($matches[1] != 'reg') {
			$matches[2] = isset($matches[2]) ? explode($matches[3], $matches[2]) : array();
		} else {
			$matches[2] = isset($matches[2]) ? array($matches[2]) : array();
		}
		return $matches;
	}

	/**
	 * 调用一个方法或者函数(无论方法是静态还是动态，是私有还是保护还是公有的都可以调用)
	 * 所有示例：
	 * 1.调用类的静态方法
	 * $ret=$this->call('UserModel::encodePassword', $args);
	 * 2.调用类的方法
	 * $ret=$this->call(array('UserModel','checkPassword), $args);
	 * 3.调用用户自定义方法
	 * $ret=$this->call('cleanJs', $args);
	 * 4.调用系统函数
	 * $ret=$this->call('var_dump', $args);
	 * @param type $func
	 * @param type $args
	 * @return boolean
	 */
	public static function call($func, $args) {
		if (is_array($func)) {
			return self::callMethod($func, $args);
		} elseif (function_exists($func)) {
			return call_user_func_array($func, $args);
		} elseif (stripos($func, '::')) {
			$_func = explode('::', $func);
			return self::callMethod($_func, $args);
		}
		return null;
	}

	private static function callMethod($_func, $args) {
		$class = $_func[0];
		$method = $_func[1];
		if (is_object($class)) {
			$class = new ReflectionClass(get_class($class));
		} else {
			$class = new ReflectionClass($class);
		}
		$obj = $class->newInstanceArgs();
		$method = $class->getMethod($method);
		$method->setAccessible(true);
		return $method->invokeArgs($obj, $args);
	}

	private static function checkRule($_rule, $val, $data, $db = null) {
		if (!$db) {
			$db = MpLoader::instance()->database();
		}
		$matches = self::getCheckRuleInfo($_rule);
		$_rule = $matches[1];
		$args = $matches[2];
		switch ($_rule) {
			case 'required':
				return !empty($val);
			case 'match':
				return isset($args[0]) && isset($data[$args[0]]) ? $val && ($val == $data[$args[0]]) : false;
			case 'equal':
				return isset($args[0]) ? $val && ($val == $args[0]) : false;
			case 'enum':
				return in_array($val, $args);
			case 'unique':#比如unique[user.name] , unique[user.name,id:1]
				if (!$val || !count($args)) {
					return false;
				}
				$_info = explode('.', $args[0]);
				if (count($_info) != 2) {
					return false;
				}
				$table = $_info[0];
				$col = $_info[1];
				if (isset($args[1])) {
					$_id_info = explode(':', $args[1]);
					if (count($_id_info) != 2) {
						return false;
					}
					$id_col = $_id_info[0];
					$id = $_id_info[1];
					$id = stripos($id, '#') === 0 ? MpInput::get_post(substr($id, 1)) : $id;
					$where = array($col => $val, "$id_col <>" => $id);
				} else {
					$where = array($col => $val);
				}
				return !$db->where($where)->from($table)->count_all_results();
			case 'exists':#比如exists[user.name] , exists[user.name,type:1], exists[user.name,type:1,sex:#sex]
				if (!$val || !count($args)) {
					return false;
				}
				$_info = explode('.', $args[0]);
				if (count($_info) != 2) {
					return false;
				}
				$table = $_info[0];
				$col = $_info[1];
				$where = array($col => $val);
				if (count($args) > 1) {
					foreach (array_slice($args, 1) as $v) {
						$_id_info = explode(':', $v);
						if (count($_id_info) != 2) {
							continue;
						}
						$id_col = $_id_info[0];
						$id = $_id_info[1];
						$id = stripos($id, '#') === 0 ? MpInput::get_post(substr($id, 1)) : $id;
						$where[$id_col] = $id;
					}
				}

				return $db->where($where)->from($table)->count_all_results();
			case 'min_len':
				return isset($args[0]) ? (mb_strlen($val, 'UTF-8') >= intval($args[0])) : false;
			case 'max_len':
				return isset($args[0]) ? (mb_strlen($val, 'UTF-8') <= intval($args[0])) : false;
			case 'range_len':
				return count($args) == 2 ? (mb_strlen($val, 'UTF-8') >= intval($args[0])) && (mb_strlen($val, 'UTF-8') <= intval($args[1])) : false;
			case 'len':
				return isset($args[0]) ? (mb_strlen($val, 'UTF-8') == intval($args[0])) : false;
			case 'min':
				return isset($args[0]) && is_numeric($val) ? $val >= $args[0] : false;
			case 'max':
				return isset($args[0]) && is_numeric($val) ? $val <= $args[0] : false;
			case 'range':
				return (count($args) == 2) && is_numeric($val) ? $val >= $args[0] && $val <= $args[1] : false;
			case 'alpha':#纯字母
				return !preg_match('/[^A-Za-z]+/', $val);
			case 'alpha_num':#纯字母和数字
				return !preg_match('/[^A-Za-z0-9]+/', $val);
			case 'alpha_dash':#纯字母和数字和下划线和-
				return !preg_match('/[^A-Za-z0-9_-]+/', $val);
			case 'alpha_start':#以字母开头
				return preg_match('/^[A-Za-z]+/', $val);
			case 'num':#纯数字
				return !preg_match('/[^0-9]+/', $val);
			case 'int':#整数
				return preg_match('/^([-+]?[1-9]\d*|0)$/', $val);
			case 'float':#小数
				return preg_match('/^([1-9]\d*|0)\.\d+$/', $val);
			case 'numeric':#数字-1，1.2，+3，4e5
				return is_numeric($val);
			case 'natural':#自然数0，1，2，3，12，333
				return preg_match('/^([1-9]\d*|0)$/', $val);
			case 'natural_no_zero':#自然数不包含0
				return preg_match('/^[1-9]\d*$/', $val);
			case 'email':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $val) : $args[0];
			case 'url':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/', $val) : $args[0];
			case 'qq':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^[1-9][0-9]{4,}$/', $val) : $args[0];
			case 'phone':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^(?:\d{3}-?\d{8}|\d{4}-?\d{7})$/', $val) : $args[0];
			case 'mobile':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1}))+\d{8})$/', $val) : $args[0];
			case 'zipcode':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^[1-9]\d{5}(?!\d)$/', $val) : $args[0];
			case 'idcard':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^\d{14}(\d{4}|(\d{3}[xX])|\d{1})$/', $val) : $args[0];
			case 'ip':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/', $val) : $args[0];
			case 'chs':
				$count = implode(',', array_slice($args, 1, 2));
				$count = empty($count) ? '1,' : $count;
				$can_empty = isset($args[0]) && $args[0] == 'true';
				return !empty($val) ? preg_match('/^[\x{4e00}-\x{9fa5}]{' . $count . '}$/u', $val) : $can_empty;
			case 'date':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/', $val) : $args[0];
			case 'time':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^(([0-1][0-9])|([2][0-3])):([0-5][0-9])(:([0-5][0-9]))$/', $val) : $args[0];
			case 'datetime':
				$args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				return !empty($val) ? preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30))) (([0-1][0-9])|([2][0-3])):([0-5][0-9])(:([0-5][0-9]))$/', $val) : $args[0];

			case 'reg':#正则表达式验证,reg[/^[\]]$/i]
				/**
				 * 模式修正符说明:
				  i	表示在和模式进行匹配进不区分大小写
				  m	将模式视为多行，使用^和$表示任何一行都可以以正则表达式开始或结束
				  s	如果没有使用这个模式修正符号，元字符中的"."默认不能表示换行符号,将字符串视为单行
				  x	表示模式中的空白忽略不计
				  e	正则表达式必须使用在preg_replace替换字符串的函数中时才可以使用(讲这个函数时再说)
				  A	以模式字符串开头，相当于元字符^
				  Z	以模式字符串结尾，相当于元字符$
				  U	正则表达式的特点：就是比较“贪婪”，使用该模式修正符可以取消贪婪模式
				 */
				return !empty($args[0]) ? preg_match($args[0], $val) : false;
			/**
			 * set set_post不参与验证，返回true跳过
			 * 
			 * 说明：
			 * set用于设置在验证数据前对数据进行处理的函数或者方法
			 * set_post用于设置在验证数据后对数据进行处理的函数或者方法
			 * 如果设置了set，数据在验证的时候验证的是处理过的数据
			 * 如果设置了set_post，可以通过第三个参数$data接收数据：$this->checkData($rule, $_POST, $data)，$data是验证通过并经过set_post处理后的数据
			 * set和set_post后面是一个或者多个函数或者方法，多个逗号分割
			 * 注意：
			 * 1.无论是函数或者方法都必须有一个字符串返回
			 * 2.如果是系统函数，系统会传递当前值给系统函数，因此系统函数必须是至少接受一个字符串参数，比如md5，trim
			 * 3.如果是自定义的函数，系统会传递当前值和全部数据给自定义的函数，因此自定义函数可以接收两个参数第一个是值，第二个是全部数据$data
			 * 4.如果是类的方法写法是：类名称::方法名 （方法静态动态都可以，public，private，都可以）
			 */
			case 'set':
			case 'set_post':
				return true;
			default:
				$_args = array_merge(array($val, $data), $args);
				$matches = self::getCheckRuleInfo($_rule);
				$func = $matches[1];
				$args = $matches[2];
				if (function_exists($func)) {
					$reflection = new ReflectionFunction($func);
					//如果是系统函数
					if ($reflection->isInternal()) {
						$_args = isset($_args[0]) ? array($_args[0]) : array();
					}
				}
				return self::call($_rule, $_args);
		}
		return false;
	}

}
