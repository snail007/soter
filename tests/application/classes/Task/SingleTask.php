<?php

class Task_SingleTask extends Soter_Task_Single {

	public function execute(\Soter_CliArgs $args) {
		echo "called \n";
		sleep(5);
	}

}
