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

	public function getHvmcModuleName() {
		return $this->hvmcModuleName;
	}

	public function setHvmcModuleName($hmvcModuleName) {
		$this->hvmcModuleName = $hmvcModuleName;
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
		$uri = $uri ? $uri : Soter_Tools::issetGet($_SERVER, 'REQUEST_URI', '/');

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
		} else {
			$uri = '';
		}
		if (empty($uri)) {
			//没有找到hmvc模块名称，或者控制器名称
			return $this->route->setFound(FALSE);
		}
		//到此$uri形如：Welcome/index.do , Welcome/User , Welcome
		//hmvc检测 
		$_info = explode('/', $uri);
		$hmvcModule = current($_info);
		if ($hmvcModuleDirName=Soter::checkHmvc($hmvcModule,FALSE)) {
			//找到hmvc模块,去除hmvc模块名称，得到真正的路径
			$uri = ltrim(substr($uri, strlen($hmvcModule)), '/');
		}

		//首先控制器名和方法名初始化为默认
		$controller = $config->getDefaultController();
		$method = $config->getDefaultMethod();
		/**
		 * 到此，如果上面$uri被去除掉hvmc模块名称后，$uri有可能是空
		 * 或者$uri有控制器名称或者方法名称
		 * 形如：Welcome/index.do , Welcome/User , Welcome
		 */
		if ($uri) {
			$subfix = $config->getMethodUriSubfix();
			//解析路径
			$methodPathArr = explode($subfix, $uri);
			//找到了控制器名和方法名
			if (count($methodPathArr) == 2 && empty($methodPathArr[1])) {
				//覆盖上面的默认控制器名和方法名
				$controller = str_replace('/', '_', dirname($uri));
				$method = basename($methodPathArr[0], $subfix);
			} elseif (!empty($methodPathArr[0])) {
				//只找到了控制器名，覆盖上面的默认控制器名
				$controller = str_replace('/', '_', $uri);
			}
		}
		$controller = $config->getControllerDirName() . '_' . $controller;
		$method = $config->getMethodPrefix() . $method;
		return $this->route
				->setHvmcModuleName($hmvcModuleDirName)
				->setController($controller)
				->setMethod($method)
				->setFound(TRUE);
	}

}

class Soter_Tools {

	static function issetGet($arr, $key, $default = NULL) {
		return isset($arr[$key]) ? $arr[$key] : $default;
	}

}

/**
 * @property Soter_Exception_Handle $exceptionHandle
 */
class Soter_Config {

	private $applicationDir = '', //项目目录
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
		$uriReWriterContainer = array(),
		$exceptionHandle, $route, $environment = Sr::ENV_DEVELOPMENT,
		$serverEnvironmentTestingValue = 'testing',
		$serverEnvironmentDevelopmentValue = 'development',
		$serverEnvironmentProductionValue = 'production',
		$hmvcModules = array();

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

	public function getUriReWriter() {
		return $this->uriReWriterContainer;
	}

	public function addUriReWriter($uriReWriter) {
		$this->uriReWriterContainer[] = $uriReWriter;
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

	public function write(Soter_Exception $exception) {
		
	}

}
