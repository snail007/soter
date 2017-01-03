<?php

class Exception_HandleTest implements Soter_Exception_Handle {

	public function handle(Soter_Exception $exception) {
		
		echo $exception->renderJson();
	}

//put your code here
}
