<?php

namespace Business;

class TestBusiness0 extends \Soter_Business {

	public function dbError() {
		\Sr::db()
			->select('count(*) as total')
			->from('test12')
			->execute();
	}

}
