<?php

abstract class Soter_Controller {
	
}

abstract class Soter_Dao {
	
}

abstract class Soter_Business {
	
}

abstract class Soter_Task {

	abstract function execute(Soter_CliArgs $args);
}

/**
 * @property Soter_Route $route
 */
abstract class Soter_Router {

	protected $route;

	public function __construct() {
		$this->route = new Soter_Route();
	}

	/**
	 * 
	 * @param Soter_Request $Soter_Request
	 * @return \Soter_Route
	 */
	public abstract function find();

	public function &route() {
		return $this->route;
	}

}

abstract class Soter_Exception extends Exception {

	protected $errorMessage, $errorCode, $errorFile, $errorLine, $errorType, $trace,
		$httpStatusLine = 'HTTP/1.0 500 Internal Server Error',
		$exceptionName = 'Soter_Exception';

	public function __construct($errorMessage = '', $errorCode = '-1', $errorType = 'Exception', $errorFile = '', $errorLine = '0') {
		parent::__construct($errorMessage, $errorCode);
		$this->errorMessage = $errorMessage;
		$this->errorCode = $errorCode;
		$this->errorType = $errorType;
		$this->errorFile = Sr::realPath($errorFile);
		$this->errorLine = $errorLine;
		$this->trace = debug_backtrace(false);
	}

	public function errorType2string($errorType) {
		$value = $errorType;
		$levelNames = array(
		    E_ERROR => 'ERROR', E_WARNING => 'WARNING',
		    E_PARSE => 'PARSE', E_NOTICE => 'NOTICE',
		    E_CORE_ERROR => 'CORE_ERROR', E_CORE_WARNING => 'CORE_WARNING',
		    E_COMPILE_ERROR => 'COMPILE_ERROR', E_COMPILE_WARNING => 'COMPILE_WARNING',
		    E_USER_ERROR => 'USER_ERROR', E_USER_WARNING => 'USER_WARNING',
		    E_USER_NOTICE => 'USER_NOTICE');
		if (defined('E_STRICT')) {
			$levelNames[E_STRICT] = 'STRICT';
		}
		if (defined('E_DEPRECATED')) {
			$levelNames[E_DEPRECATED] = 'DEPRECATED';
		}
		if (defined('E_USER_DEPRECATED')) {
			$levelNames[E_USER_DEPRECATED] = 'USER_DEPRECATED';
		}
		if (defined('E_RECOVERABLE_ERROR')) {
			$levelNames[E_RECOVERABLE_ERROR] = 'RECOVERABLE_ERROR';
		}
		$levels = array();
		if (($value & E_ALL) == E_ALL) {
			$levels[] = 'E_ALL';
			$value&=~E_ALL;
		}
		foreach ($levelNames as $level => $name) {
			if (($value & $level) == $level) {
				$levels[] = $name;
			}
		}
		if (empty($levelNames[$this->errorCode])) {
			return $this->errorType ? $this->errorType : 'General Error';
		}
		return implode(' | ', $levels);
	}

	public function getErrorMessage() {
		return $this->errorMessage;
	}

	public function getErrorCode() {
		return $this->errorCode;
	}

	public function getErrorFile($safePath = FALSE) {
		return $safePath ? $this->safePath($this->errorFile) : $this->errorFile;
	}

	private function safePath($path) {
		if (!$path) {
			return '';
		}
		$path = Sr::realPath($path);
		$siteRoot = Sr::realPath(Sr::arrayGet($_SERVER, 'DOCUMENT_ROOT'));
		$_path = str_replace($siteRoot, '', $path);
		$relPath = str_replace($siteRoot, '', rtrim(Soter::getConfig()->getApplicationDir(), '/'));
		return '~APPPATH~' . str_replace($relPath, '', $_path);
	}

	public function getErrorLine() {
		return $this->errorLine;
	}

	public function getErrorType() {
		return $this->errorType2string($this->errorCode);
	}

	public function render($isJson = FALSE, $return = FALSE) {
		if ($isJson) {
			$string = $this->renderJson();
		} elseif (Sr::isCli()) {
			$string = $this->renderCli();
		} else {
			$string = str_replace('</body>', $this->getTraceString(FALSE) . '</body>', $this->renderHtml());
		}
		if ($return) {
			return $string;
		} else {
			echo $string;
		}
	}

	public function getTraceCliString() {
		return $this->getTraceString(TRUE);
	}

	public function getTraceHtmlString() {
		return $this->getTraceString(FALSE);
	}

	private function getTraceString($isCli) {
		$trace = $this->trace;
		array_shift($trace);
		$trace = array_reverse($trace);
		$str = $isCli ? "[ Debug Backtrace ]\n" : '<div style="padding:10px;">[ Debug Backtrace ]<br/>';
		foreach ($trace as $e) {
			array_shift($trace);
			if (Sr::arrayGet($e, 'function') == 'call_user_func_array') {
				break;
			}
		}
		if (empty($trace)) {
			return '';
		}
		foreach ($trace as $e) {
			if (!empty($e['class']) && stripos($e['class'], 'Soter_') === 0) {
				break;
			}
			$file = $this->safePath(Sr::arrayGet($e, 'file'));
			$line = Sr::arrayGet($e, 'line');
			$func = (!empty($e['class']) ? "{$e['class']}{$e['type']}{$e['function']}()" : "{$e['function']}()");
			$str.="&rarr; {$func} " . ($line ? "[ line:{$line} {$file} ]" : '') . ($isCli ? "\n" : '<br/>');
		}
		$str.=$isCli ? "\n" : '</div>';
		return $str;
	}

	public function renderCli() {
		return "$this->exceptionName [ " . $this->getErrorType() . " ]\n"
			. "Line: " . $this->getErrorLine() . ". " . $this->getErrorFile() . "\n"
			. "Message: " . $this->getErrorMessage() . "\n"
			. "Time: " . date('Y/m/d H:i:s T') . "\n";
	}

	public function renderHtml() {
		return '<body style="padding:0;margin:0;background:black;color:whitesmoke;">'
			. '<div style="padding:10px;background:red;font-size:18px;">' . $this->exceptionName . ' [ ' . $this->getErrorType() . ' ] </div>'
			. '<div style="padding:10px;background:black;font-size:14px;color:yellow;line-height:1.5em;">'
			. '<font color="whitesmoke">Line: </font>' . $this->getErrorLine() . ' [ ' . $this->getErrorFile(TRUE) . ' ]<br/>'
			. '<font color="whitesmoke">Message: </font>' . htmlspecialchars($this->getErrorMessage()) . '</br>'
			. '<font color="whitesmoke">Time: </font>' . date('Y/m/d H:i:s T') . '</div>'
			. '</body>';
	}

	public function renderJson() {
		$config = soter::getConfig();
		$json[$config->getExcptionErrorJsonFileName()] = $this->getErrorFile();
		$json[$config->getExcptionErrorJsonLineName()] = $this->getErrorLine();
		$json[$config->getExcptionErrorJsonMessageName()] = $this->getErrorMessage();
		$json[$config->getExcptionErrorJsonTypeName()] = $this->getErrorType();
		$json[$config->getExcptionErrorJsonCodeName()] = $this->getErrorCode();
		$json[$config->getExcptionErrorJsonTimeName()] = date('Y/m/d H:i:s T');
		$json[$config->getExcptionErrorJsonTraceName()] = $this->getTraceCliString();
		$output = json_encode($json);
		return $output;
	}

	public function setHttpHeader() {
		header($this->httpStatusLine);
		return $this;
	}

	public function __toString() {
		return $this->render(FALSE, TRUE);
	}

}
