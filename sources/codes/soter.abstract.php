<?php

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

    public function route() {
        return $this->route;
    }

}

abstract class Soter_Exception extends Exception {

    protected $errorMessage, $errorCode, $errorFile, $errorLine, $errorType,
            $httpStatusLine = 'HTTP/1.0 500 Internal Server Error',
            $exceptionName = 'Soter_Exception';

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
        return $this->errorMessage;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function getErrorFile($safePath = FALSE) {
        if ($safePath) {
            $siteRoot = Sr::arrayGet($_SERVER, 'DOCUMENT_ROOT');
            $path = Sr::realPath(str_replace($siteRoot, '', $this->errorFile));
            $relPath = Sr::realPath(str_replace($siteRoot, '', Soter::getConfig()->getApplicationDir()));
            return str_replace($relPath, '', $path);
        }
        return $this->errorFile;
    }

    public function getErrorLine() {
        return $this->errorLine;
    }

    public function getErrorType() {

        return $this->errorType2string($this->errorCode);
    }

    public function render($isJson = FALSE) {
        if (!Sr::isCli()) {
            echo $isJson ? $this->renderJson() : $this->renderHtml();
        } else {
            echo $this->renderCli();
        }
    }

    public function renderCli() {
        return "$this->exceptionName [ " . $this->getErrorType() . " ]\n"
                . "Line: $this->errorLine ~APPPATH~" . $this->errorFile . "\n"
                . "Message: $this->errorMessage\n";
    }

    public function renderHtml() {
        return '<body style="padding:0;margin:0;background:black;color:whitesmoke;">'
                . '<div style="padding:10px;background:red;font-size:18px;">' . $this->exceptionName . ' [ ' . $this->getErrorType() . ' ] </div>'
                . '<div style="padding:10px;background:black;font-size:14px;color:yellow;line-height:1.5em;">'
                . '<font color="whitesmoke">Line: </font>' . $this->errorLine . ' [ ~APPPATH~' . $this->getErrorFile(TRUE) . ' ]<br/>'
                . '<font color="whitesmoke">Message: </font>' . htmlspecialchars($this->errorMessage) . '</div>'
                . '</body>';
    }

    public function renderJson() {
        $config = soter::getConfig();
        $json[$config->getExcptionErrorJsonFileName()] = $this->errorFile;
        $json[$config->getExcptionErrorJsonLineName()] = $this->errorLine;
        $json[$config->getExcptionErrorJsonMessageName()] = $this->errorMessage;
        $json[$config->getExcptionErrorJsonTypeName()] = $this->getErrorType();
        $json[$config->getExcptionErrorJsonCodeName()] = $this->errorCode;
        $output = json_encode($json);
        return $output;
    }

    public function setHttpHeader() {
        header($this->httpStatusLine);
        return $this;
    }

}
