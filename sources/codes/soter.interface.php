<?php

interface Soter_Logger_Writer {

    public function write(Soter_Exception $exception);
}

interface Soter_Exception_Handle {

    public function handle(Soter_Exception $exception);
}
