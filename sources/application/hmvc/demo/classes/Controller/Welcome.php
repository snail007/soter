<?php

class Controller_Welcome {

	public function do_index() {
		echo Soter::getConfig()->getApplicationDir();
		// xxcc();
		new Misc();
		return 'hmvc';
	}

}
