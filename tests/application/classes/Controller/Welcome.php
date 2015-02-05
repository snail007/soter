<?php

class Controller_Welcome extends Soter_Controller {

	public function do_index() {
		Sr::dao('TestDao');
		Sr::business('TestBusiness');
		Sr::model('TestModel');
		Sr::library('MyClass');
		Sr::factory('MyClass');
		Sr::factory('Misc');
		echo Sr::config('config.host');
		//Sr::functions('functions');
		myFunction();
	}

	public function index() {
		xxx();
		Sr::dump(Soter::getConfig()->getRequest());
	}

}
