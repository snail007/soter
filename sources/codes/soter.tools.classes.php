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
        
    }

}

class Soter_Response {
    
}

class Soter_Route {

    private $found = false;

    public function found() {
        return $this->found;
    }

    public function setFound($found) {
        $this->found = $found;
    }

    private $controller, $method, $args, $filePath;

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

    public function __construct($controller, $method, $args = array(), $filePath = '') {
        $this->controller = $controller;
        $this->method = $method;
        $this->args = $args;
        $this->filePath = $filePath;
    }

}

class Soter_Router {

    private $route;

    /**
     * 
     * @param Soter_Request $Soter_Request
     * @return \Soter_Route
     */
    public function find(Soter_Request $Soter_Request) {

        $uri = $Soter_Request->getUri();
        //解析uri
        //xx

        $route = new Soter_Route('Controller_Welcome', 'do_index');
        $this->route = $route;
        //$this->route->setFound(true);
        return $this->route;
    }

    public function route() {
        return $this->route;
    }

}

class Soter_Tools {

    static function issetGet($arr, $key, $default = NULL) {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }

}

class Soter_Config {

    private $timeZone = 'PRC',
            $isRewrite = FALSE,
            $applicationPath,
            $request, $showError = true,
            $excptionErrorJsonMessageName = 'errorMessage',
            $excptionErrorJsonFileName = 'errorFile',
            $excptionErrorJsonLineName = 'errorLine',
            $excptionErrorJsonTypeName = 'errorType',
            $excptionErrorJsonCodeName = 'errorCode',
            $routersContainer = array(),
            $loggerWriterContainer = array();

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

    public function getApplicationPath() {
        return $this->applicationPath;
    }

    public function setApplicationPath($applicationPath) {
        $this->applicationPath = $applicationPath;
        return $this;
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

class Soter_Environment {

    public static function isCli() {
        return PHP_SAPI == 'cli';
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
            $this->dispatch(new Soter_Exception($exception->getMessage(), $exception->getCode(), get_class($exception), $exception->getFile(), $exception->getLine()));
        }
    }

    final public function handleError($code, $message, $file, $line) {
        if (0 == error_reporting()) {
            return;
        }
        $this->dispatch(new Soter_Exception($message, $code, 'General Error', $file, $line));
    }

    final public function handleFatal() {
        if (0 == error_reporting()) {
            return;
        }
        $lastError = error_get_last();
        $fatalError = array(1, 256, 64, 16, 4, 4096);
        if (!isset($lastError["type"]) || !in_array($lastError["type"],$fatalError)) {
            return;
        }
        $this->dispatch(new Soter_Exception($lastError['message'], $lastError['type'], 'Fatal Error', $lastError['file'], $lastError['line']));
    }

    final public function dispatch(Soter_Exception $exception) {
        $loggerWriters = Soter::getConfig()->getLoggerWriters();
        foreach ($loggerWriters as $loggerWriter) {
            $loggerWriter->write($exception);
        }
        exit();
    }

}

class Soter_Logger_FileWriter implements Soter_Logger_Writer {

    public function write(Soter_Exception $exception) {
        
    }

}

class Soter_Logger_PrinterWriter implements Soter_Logger_Writer {

    public function write(Soter_Exception $exception) {
        Sr::dump($exception->render());
    }

}
