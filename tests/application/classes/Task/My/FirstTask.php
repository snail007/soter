<?php
class  Task_My_FirstTask extends Soter_Task {
	public function execute(Soter_CliArgs $args) {
		Sr::dump($args->get());
	}
}