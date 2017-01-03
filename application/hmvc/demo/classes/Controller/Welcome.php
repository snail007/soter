<?php

class Controller_Welcome extends Soter_Controller {

	public function do_index() {

		return Sr::view()->loadParent('index');
	}

}
