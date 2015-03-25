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
		Soter::getConfig()->setShowError(FALSE);
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
		$config = Soter::getConfig();
		ini_set('display_errors', TRUE);
		$loggerWriters = $config->getLoggerWriters();
		foreach ($loggerWriters as $loggerWriter) {
			$loggerWriter->write($exception);
		}
		$handle = $config->getExceptionHandle();
		if ($handle instanceof Soter_Exception_Handle) {
			$handle->handle($exception);
		} elseif ($config->getShowError()) {
			$exception->render();
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

class Soter_Maintain_Default_Handle implements Soter_Maintain_Handle {

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
		return @serialize(array(
			    'userData' => $userData,
			    'expireTime' => time() + $cacheTime
		));
	}

	private function unpack($cacheData) {
		$cacheData = @unserialize($cacheData);
		if (is_array($cacheData) && isset($cacheData['userData']) && isset($cacheData['expireTime'])) {
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
