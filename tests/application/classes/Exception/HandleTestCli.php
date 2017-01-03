<?php

class Exception_HandleTestCli implements Soter_Exception_Handle {

	public function handle(Soter_Exception $exception) {
		
		echo $exception->renderCli();
	}

//put your code here
}
