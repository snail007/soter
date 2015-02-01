<?php

class Soter_Exception_404 extends Soter_Exception {

    protected $exceptionName = 'Soter_Exception_404',
            $httpStatusLine = 'HTTP/1.0 404 Not Found';
}

class Soter_Exception_500 extends Soter_Exception {

    protected $exceptionName = 'Soter_Exception_500',
            $httpStatusLine = 'HTTP/1.0 500 Internal Server Error';

}

class Soter_Exception_Database extends Soter_Exception {

    protected $exceptionName = 'Soter_Exception_Database',
            $httpStatusLine = 'HTTP/1.0 500 Internal Server Error';

}
