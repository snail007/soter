<?php

class Soter_Exception_404 extends Soter_Exception {

	protected $exceptionName = 'Soter 404 Exception';

	public function getExceptionName() {
		return $this->exceptionName;
	}

	public function setHttpCode() {
		header('HTTP/1.0 404 Not Found');
	}

}
