<?php

class Controller_Welcome extends Soter_Controller{

	public function do_index() {
		myFunction();
		Sr::dao('TestDao');
		Sr::business('TestBusiness');
		// xxcc();
		new Misc();
		return 'hmvc';
	}

}
