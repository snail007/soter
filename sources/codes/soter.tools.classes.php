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

	private $controller, $method;

	public function getController() {
		return $this->controller;
	}

	public function getMethod() {
		return $this->method;
	}

	public function setController($controller) {
		$this->controller = $controller;
		return $this;
	}

	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}

}

class Soter_Router {

	private $isOkay = false;

	public function isOkay() {
		return $this->isOkay;
	}

	/**
	 * 
	 * @param Soter_Request $Soter_Request
	 * @return \Soter_Route
	 */
	public function route(Soter_Request $Soter_Request) {
		$route = new Soter_Route;
		$uri = $Soter_Request->getUri();
		$this->setIsOkay(true);
		return $route;
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
		$request,
		$excptionErrorJsonMessageName = 'errorMessage',
		$excptionErrorJsonFileName = 'errorFile',
		$excptionErrorJsonLineName = 'errorLine',
		$excptionErrorJsonTypeName = 'errorType',
		$excptionErrorJsonCodeName = 'errorCode'
	;
	private $routersContainer = array();

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

	public function getApplicationPath() {
		return $this->applicationPath;
	}

	public function setApplicationPath($applicationPath) {
		$this->applicationPath = $applicationPath;
		return $this;
	}

	public function getRouters() {
		return $this->routersContainer;
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
