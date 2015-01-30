<?php

abstract class Soter_Exception extends Exception {

	protected $errorFile, $errorLine, $errorMessage, $errorCode, $errorType;

	public function getErrorFile() {
		return $this->errorFile;
	}

	public function getErrorLine() {
		return $this->errorLine;
	}

	public function getErrorMessage() {
		return $this->errorMessage;
	}

	public function getErrorCode() {
		return $this->errorCode;
	}

	public function getErrorType() {
		return $this->errorType;
	}

	public function setErrorFile($errorFile) {
		$this->errorFile = $errorFile;
		return $this;
	}

	public function setErrorLine($errorLine) {
		$this->errorLine = $errorLine;
		return $this;
	}

	public function setErrorMessage($errorMessage) {
		$this->errorMessage = $errorMessage;
		return $this;
	}

	public function setErrorCode($errorCode) {
		$this->errorCode = $errorCode;
		return $this;
	}

	public function setErrorType($errorType) {
		$this->errorType = $errorType;
		return $this;
	}

	public function render($isReturn = false, $isSetHttpHeader = true, $isJson = false) {
		if ($isSetHttpHeader) {
			$this->_setHttpCode();
		}
		$isCli = Soter_Environment::isCli();
		$output = '';
		if ($isJson) {
			$config = soter::getConfig();
			$json[$config->getExcptionErrorJsonFileName()] = $this->errorFile;
			$json[$config->getExcptionErrorJsonLineName()] = $this->errorLine;
			$json[$config->getExcptionErrorJsonMessageName()] = $this->errorMessage;
			$json[$config->getExcptionErrorJsonTypeName()] = $this->errorType;
			$json[$config->getExcptionErrorJsonCodeName()] = $this->errorCode;
			$output = json_encode($json);
		} else {
			$output = ($isCli ? '' : '<dl>')
				. ($isCli ? '' : '<dt>') . 'Exception:' . $this->getExceptionName() . ($isCli ? "\n" : '</dt>')
				. ($isCli ? '' : '<dd>') . 'Message:' . $this->errorMessage . ($isCli ? "\n" : '</dd>')
				. ($isCli ? '' : '<dd>') . 'File:' . $this->errorFile . ($isCli ? "\n" : '</dd>')
				. ($isCli ? '' : '<dd>') . 'Line:' . $this->errorLine . ($isCli ? "\n" : '</dd>')
				. ($isCli ? '' : '<dd>') . 'Type:' . $this->errorType . ($isCli ? "\n" : '</dd>')
				. ($isCli ? '' : '<dd>') . 'Code:' . $this->errorCode . ($isCli ? "\n" : '</dd>')
				. ($isCli ? '' : '</dl>');
		}
		if ($isReturn) {
			return $output;
		} else {
			exit($output);
		}
	}

	private function _setHttpCode() {
		if (Soter_Environment::isCli()) {
			$this->setHttpCode();
		}
	}

	abstract function getExceptionName();

	abstract function setHttpCode();
}
