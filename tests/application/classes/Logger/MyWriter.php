<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyWriter
 *
 * @author pm
 */
class Logger_MyWriter implements Soter_Logger_Writer {

    public function write(Soter_Exception $exception) {
	
        echo('my writer called');
    }

//put your code here
}
