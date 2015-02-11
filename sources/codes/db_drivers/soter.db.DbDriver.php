<?php

class Soter_Db_Config {

	private $isPconnect = FALSE,
		$isShowError = TRUE,
		$database = '',
		$charset = 'utf8',
		$dbCollate = 'utf8_general_ci'

	;

	public function getCharset() {
		return $this->charset;
	}

	public function getDbCollate() {
		return $this->dbCollate;
	}

	public function setCharset($charset) {
		$this->charset = $charset;
		return $this;
	}

	public function setDbCollate($dbCollate) {
		$this->dbCollate = $dbCollate;
		return $this;
	}

	public function getDatabase() {
		return $this->database;
	}

	public function setDatabase($database) {
		$this->database = $database;
		return $this;
	}

	public function getIsShowError() {
		return $this->isShowError;
	}

	public function setIsShowError($isShowError) {
		$this->isShowError = $isShowError;
		return $this;
	}

	public function getIsPconnect() {
		return $this->isPconnect;
	}

	public function setIsPconnect($isPconnect) {
		$this->isPconnect = $isPconnect;
		return $this;
	}

}

/**
 * @property Soter_Db_Config $config
 */
abstract class Soter_DbDriver {

	private
	//连接资源句柄
		$connId,
		$config

	;

	public function &getConfig() {
		return $this->config;
	}

	public function setConfig($config) {
		$this->config = $config;
		return $this;
	}

	public function &getConnId() {
		if (!is_resource($this->connId) || !is_object($this->connId)) {
			$this->initialize();
		}
	}

	public function setConnId($connId) {
		$this->connId = $connId;
		return $this;
	}

	protected function initialize() {
		$config = $this->getConfig();
		if (!is_resource($this->getConnId()) or ! is_object($this->getConnId())) {
			// 连接数据库
			$this->setConnId(($config->getIsPconnect() == FALSE) ? $this->db_connect() : $this->db_pconnect());
			$this->checkError($this->getConnId(), 'Can not connenct to database server.[ ' . $this->errorMessage() . ' ]');
		}
		if ($config->getDatabase() != '') {
			return $this->dbSelect() && $this->dbSetCharset();
		}
		return TRUE;
	}

	private function checkError($isOkay, $message) {
		$config = $this->getConfig();
		if (!$isOkay && Sr::config()->getShowError() && $config->getIsShowError()) {
			throw new Soter_Exception_Database($message);
		}
	}

	private function dbSelect() {
		$this->checkError($this->_dbSelect(), 'Unable to select database: ' . $this->getConfig()->getDatabase() . ' [ ' . $this->errorCode() . ' ' . $this->errorMessage() . ' ]');
	}

	private function dbSetCharset() {
		$this->checkError($this->_dbSetCharset(), 'Unable to set database connection charset: ' . $this->getConfig()->getCharset() . ' [ ' . $this->errorCode() . ' ' . $this->errorMessage() . ' ]');
	}

	function version() {
		$this->checkError($sql=$this->_version(), 'unknown version');
	}

	public abstract function affectedRows();

	public abstract function insertId();

	public abstract function count(Array $where = array());

	public abstract function transBegin();

	public abstract function transCommit();

	public abstract function transRollback();

	public abstract function errorMessage();

	public abstract function errorCode();

	protected abstract function _listTables();

	protected abstract function _dbSelect();

	protected abstract function _dbConnect();

	protected abstract function _dbPconnect();

	protected abstract function _reconnect();

	protected abstract function _listColumns($table = '');

	protected abstract function _fieldData();

	protected abstract function _escapeIdentifiers($item);

	protected abstract function _fromTables($tables);

	protected abstract function _insert($table, $keys, $values);

	protected abstract function _replace($table, $keys, $values);

	protected abstract function _insertBatch($table, $keys, $values);

	protected abstract function _update($table, $values, $where, $orderby = array(), $limit = FALSE);

	protected abstract function _updateBatch($table, $values, $index, $where = NULL);

	protected abstract function _truncate($table);

	protected abstract function _delete($table, $where = array(), $like = array(), $limit = FALSE);

	protected abstract function _dbSetCharset($charset, $dbCollate);

	protected abstract function _limit($sql, $limit, $offset);

	protected abstract function _close($conn_id);

	protected abstract function _escapeStr($str, $like = FALSE);

	protected abstract function _version();

	protected abstract function _execute($sql);

	protected abstract function _prep_query($sql);
}
