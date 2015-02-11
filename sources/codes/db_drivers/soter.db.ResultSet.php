<?php

abstract class Soter_Db_ResultSet {

	public $connId = NULL,
		$resultId = NULL;
	private $resultAssocArray = NULL;

	public function &asObjects($className = 'stdClass') {
		if (!$this->resultId) {
			return array();
		}
		$resultObject = array();
		foreach ($this->asRows() as $row) {
			$object = new $className();
			foreach ($row as $key => $value) {
				$method = 'set' . ucfirst($key);
				if (method_exists($object, $method)) {
					$object->$method($value);
				} else {
					$object->$key = $value;
				}
			}
			$resultObject[] = $object;
		}
		return $resultObject;
	}

	public function asObject($n = 0, $className = 'stdClass') {
		$result = $this->asObjects($className);
		if (!isset($result[$n])) {
			return array();
		}
		return $result[$n];
	}

	public function &asRows($isAssoc = TRUE) {
		$result = array();
		if ($this->resultId === FALSE) {
			return array();
		}
		if (is_null($this->resultAssocArray)) {
			$this->resultAssocArray = array();
			$this->_dataSeek(0);
			while ($row = $this->_fetchAssoc()) {
				$this->resultAssocArray[] = $row;
			}
		} if ($isAssoc) {
			return $result = $this->resultAssocArray;
		} else {
			foreach ($this->resultAssocArray as $value) {
				$result[] = array_values($value);
			}
			return $result;
		}
	}

	public function asRow($n = 0, $isAssoc = TRUE) {
		$result = $this->asRows($isAssoc);
		if (!isset($result[$n])) {
			return array();
		}
		return $result[$n];
	}

	public function firstRow() {
		return $this->asRow(0);
	}

	public function firstObject($className = 'stdClass') {
		return $this->asObject(0, $className);
	}

	public function lastRow() {
		$result = $this->asRows();
		if (count($result) == 0) {
			return $result;
		}
		return $result[count($result) - 1];
	}

	public function lastOjbect($className = 'stdClass') {
		$result = $this->asObjects($className);
		if (count($result) == 0) {
			return $result;
		}
		return $result[count($result) - 1];
	}

	/**
	 * 下面的抽象方法由具体的驱动负责实现
	 */
	public abstract function fieldsCount();

	public abstract function rowsCount();

	public abstract function listFields();

	public abstract function fieldData();

	public abstract function freeResult();

	protected abstract function _dataSeek($n);

	protected abstract function _fetchAssoc();
}
