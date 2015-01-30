<?php

class Soter_Exception_404 extends Soter_Exception {

    protected $exceptionName = 'Soter_404_Exception';

    public function setHttpCode() {
        header('HTTP/1.0 404 Not Found');
    }

}

class Soter_Exception extends Exception {

    protected $errorMessage, $errorCode, $errorFile, $errorLine, $errorType, $exceptionName = 'Soter_Exception';

    public function __construct($errorMessage = '', $errorCode = '-1', $errorType = 'Exception', $errorFile = '', $errorLine = '0') {
        parent::__construct($errorMessage, $errorCode);
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
        $this->errorType = $errorType;
        $this->errorFile = $errorFile;
        $this->errorLine = $errorLine;
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
        return empty($this->errorMessage) ? $this->getMessage() : $this->errorMessage;
    }

    public function getErrorCode() {
        return empty($this->errorCode) ? $this->getCode() : $this->errorCode;
    }

    public function getErrorFile() {
        return empty($this->errorFile) ? $this->getFile() : $this->errorFile;
    }

    public function getErrorLine() {
        return empty($this->errorLine) ? $this->getLine() : $this->errorLine;
    }

    public function getErrorType() {

        return $this->errorType2string($this->errorCode);
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
            $json[$config->getExcptionErrorJsonTypeName()] = $this->getErrorType();
            $json[$config->getExcptionErrorJsonCodeName()] = $this->errorCode;
            $output = json_encode($json);
        } else {
            $output = ($isCli ? '' : '<dl">')
                    . ($isCli ? '' : '<dd><font style="font-weight:bold;" size="3">') . 'Exception:' . $this->exceptionName . ($isCli ? "\n" : '</font></dd>')
                    . ($isCli ? '' : '<dd>') . 'Message:' . $this->errorMessage . ($isCli ? "\n" : '</dd>')
                    . ($isCli ? '' : '<dd>') . 'File:' . $this->errorFile . ($isCli ? "\n" : '</dd>')
                    . ($isCli ? '' : '<dd>') . 'Line:' . $this->errorLine . ($isCli ? "\n" : '</dd>')
                    . ($isCli ? '' : '<dd>') . 'Type:' . $this->getErrorType() . ($isCli ? "\n" : '</dd>')
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
        if (!Soter_Environment::isCli()) {
            $this->setHttpCode();
        }
    }

    public function setHttpCode() {
        header('HTTP/1.0 500 Internal Server Error');
    }

}
