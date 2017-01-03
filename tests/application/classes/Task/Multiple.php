<?php

class Task_Multiple extends Soter_Task_Multiple {

	public function execute(\Soter_CliArgs $args) {
		echo "called \n";
		sleep(50);
	}

	protected function getMaxCount() {
		return 2;
	}

}
