<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Exception_Handle
 *
 * @author pm
 */
class Exception_Handle implements Soter_Exception_Handle {

    public function handle(Soter_Exception $exception) {
        echo get_class($exception);
        $exception->render();
    }

//put your code here
}
