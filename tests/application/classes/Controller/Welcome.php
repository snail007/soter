<?php

class Controller_Welcome extends Soter_Controller {

	public function do_index() {
		//echo Sr::loadConfig('config.host');
		Sr::dump(new MyClass(), new Misc());
	}

	public function index() {
		xxx();
		Sr::dump(Soter::getConfig()->getRequest());
	}

}
