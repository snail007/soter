<?php

class Soter_Exception_404 extends Soter_Exception {

    protected $exceptionName = 'Soter_404_Exception';

    public function setHttpCode() {
        header('HTTP/1.0 404 Not Found');
    }

}

class Soter_Exception_500 extends Soter_Exception {

    protected $exceptionName = 'Soter_500_Exception';

}

class Soter_Exception_Database extends Soter_Exception {

    protected $exceptionName = 'Soter_Exception_Database';

}

