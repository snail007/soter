<?php

class Dao_SessionDao extends Soter_Dao {

	public function getColumns() {
		return array(
				'id'//id
				,'data'//data
				,'timestamp'//timestamp
				);
	}

	public function getPrimaryKey() {
		return 'id';
	}

	public function getTable() {
		return 'session_handler_table';
	}

}
