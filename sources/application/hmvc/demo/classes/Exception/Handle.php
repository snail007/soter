<?php


class Exception_Handle implements Soter_Exception_Handle {

    public function handle(Soter_Exception $exception) {
        echo "<br/>Exception_Handle Called :".__FILE__.'<br/>';
        echo $exception;
    }

//put your code here
}
