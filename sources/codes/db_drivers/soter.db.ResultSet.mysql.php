<?php

class ResultSet_MySql extends Soter_Db_ResultSet {

	protected function _dataSeek($n) {
		return mysql_data_seek($this->resultId, $n);
	}

	protected function _fetchAssoc() {
		return mysql_fetch_assoc($this->resultId);
	}

	public function fieldData() {
		$retval = array();
		while ($field = mysql_fetch_object($this->resultId)) {
			preg_match('/([a-zA-Z]+)(\(\d+\))?/', $field->Type, $matches);
			$type = (array_key_exists(1, $matches)) ? $matches[1] : NULL;
			$length = (array_key_exists(2, $matches)) ? preg_replace('/[^\d]/', '', $matches[2]) : NULL;
			$F = new stdClass();
			$F->name = $field->Field;
			$F->type = $type;
			$F->default = $field->Default;
			$F->max_length = $length;
			$F->primary_key = ( $field->Key == 'PRI' ? 1 : 0 );
			$retval[] = $F;
		}
		return $retval;
	}

	public function freeResult() {
		if (is_resource($this->resultId)) {
			mysql_free_result($this->resultId);
			$this->resultId = FALSE;
		}
	}

	public function listFields() {
		$field_names = array();
		while ($field = mysql_fetch_field($this->resultId)) {
			$field_names[] = $field->name;
		}
		return $field_names;
	}

	public function fieldsCount() {
		return @mysql_num_fields($this->resultId);
	}

	public function rowsCount() {
		return @mysql_num_rows($this->resultId);
	}

}
