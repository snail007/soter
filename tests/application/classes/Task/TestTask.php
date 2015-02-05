<?php

class Task_TestTask extends Soter_Task{
	
	public function execute(Soter_CliArgs $args) {
		 
		Sr::dump($args->get('name'));
	}
}
