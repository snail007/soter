<?php

class Logger_MyWriter implements Soter_Logger_Writer {

	public function write(Soter_Exception $exception) {

		echo('<br/>my writer called :' . __FILE__ . '<br/>');
	}

}
