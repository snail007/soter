<?php

class Controller_Welcome {

	public function do_index() {

		Sr::dump(new MyClass(), new Misc());
	}

	public function index() {
		xxx();
		Sr::dump(Soter::getConfig()->getRequest());
	}

}
