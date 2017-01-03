<?php

class Business_TestBusiness extends Soter_Business {

	 
	public function dbError() {
		Sr::db()
			->select('count(*) as total')
			->from('test12')
			->execute();
	}
}
