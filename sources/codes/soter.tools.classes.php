<?php

class Soter_Request {

    private $uri;

    public function __construct() {
        if (empty($this->uri)) {
            $this->uri = Soter_Tools::issetGet($_SERVER, 'REQUEST_URI', '/');
        }
    }

    public function setUri($uri) {
        $this->uri = $uri;
        return $this;
    }

    /**
     * 获取url中的访问路径,域名后面的部分，/开头
     */
    public function getUri() {
        return $this->uri;
    }

}

class Soter_Response {
    
}

class Soter_Route {

    private $found = false;
    private $controller, $method, $args, $filePath;

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

    public function getFilePath() {
        return $this->filePath;
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

    public function setFilePath($filePath) {
        $this->filePath = $filePath;
        return $this;
    }

}

class Soter_Default_Router extends Soter_Router {

    /**
     * 
     * @return \Soter_Route
     */
    public function find() {
        $config = Soter::getConfig();
        $uri = $config->getRequest()->getUri();
        $controller = $config->getDefaultController();
        $prefix = $config->getMethodPrefix();
        $method = $config->getDefaultMethod();
        $subfix = $config->getMethodUriSubfix();
        $indexName = $config->getIndexName();
        //解析uri
        if (($pos = stripos($uri, '/' . $indexName)) !== FALSE) {
            $uri = ltrim(substr($uri, $pos + strlen('/' . $indexName)), '/');
            $_uriarr = explode('?', $uri);
            $path = trim(current($_uriarr), '/');
            $methodPathArr = explode($subfix, $path);
            if (count($methodPathArr) == 2 && empty($methodPathArr[1])) {
                $controller = str_replace('/', '_', dirname($path));
                $method = basename(current($_uriarr), $subfix);
            } else {
                $controller = str_replace('/', '_', $path);
            }
        }
        $controller = 'Controller_' . $controller;
        $method = $prefix . $method;
        return $this->route
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
            $classesName = 'classes',
            $defaultController = 'Controller_Welcome',
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
            $routersContainer = array(),
            $packageContainer = array(),
            $loggerWriterContainer = array(),
            $exceptionHandle;

    public function getExceptionHandle() {
        return $this->exceptionHandle;
    }

    public function setExceptionHandle($exceptionHandle) {
        //Soter::getConfig()->setShowError(FALSE);
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

    public function getPackageContainer() {
        return $this->packageContainer;
    }

    public function getLoggerWriterContainer() {
        return $this->loggerWriterContainer;
    }

    public function setApplicationDir($applicationDir) {
        $this->applicationDir = Sr::realPath($applicationDir) . '/';
        if (!in_array($applicationDir, $this->packageContainer)) {
            $this->addPackage($applicationDir);
        }
        return $this;
    }

    public function setIndexDir($indexDir) {
        $this->indexDir = Sr::realPath($indexDir) . '/';
        ;
        return $this;
    }

    public function setIndexName($indexName) {
        $this->indexName = $indexName;
        return $this;
    }

    public function setPackageContainer($packageContainer) {
        $this->packageContainer = $packageContainer;
        return $this;
    }

    public function setLoggerWriterContainer($loggerWriterContainer) {
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

    public function getClassesName() {
        return $this->classesName;
    }

    public function setClassesName($classesName) {
        $this->classesName = $classesName;
        return $this;
    }

    public function getPackages() {
        return $this->packageContainer;
    }

    public function addPackage($packagePath) {
        $packagePath = Sr::realPath($packagePath) . '/';
        $this->packageContainer[] = $packagePath;
        //引入配置
        if (file_exists($bootstrap = $packagePath . 'bootstrap.php')) {
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

    public function setRequest($request) {
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

    public function addRouter(Soter_Router $routersContainer) {
        $this->routersContainer[] = $routersContainer;
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
            ini_set('display_errors', FALSE);
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
