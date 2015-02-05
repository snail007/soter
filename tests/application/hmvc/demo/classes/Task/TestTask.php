<?php

class Task_TestTask extends Soter_Task{
	
	public function execute(Soter_CliArgs $args) {
		echo __FILE__;
		Sr::dump($args->get('name'));
	}
}
