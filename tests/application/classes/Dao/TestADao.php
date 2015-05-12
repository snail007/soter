<?php

class Dao_TestADao extends Soter_Dao {

	public function getColumns() {
		return array(
				'id'//id
				,'name'//name
				,'gid'//gid
				);
	}

	public function getPrimaryKey() {
		return 'id';
	}

	public function getTable() {
		return 'test_a';
	}

}
