<?php

class Exception_HandleTestHtml implements Soter_Exception_Handle {

	public function handle(Soter_Exception $exception) {
		
		echo $exception->renderHtml();
	}

//put your code here
}
