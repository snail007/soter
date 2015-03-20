<?php

/**
 * SoterPDO is simple and smart wrapper for PDO
 */
class Soter_PDO {

	protected $transactionCount = 0;

	public function beginTransaction() {
		if (!$this->transactionCounter++) {
			return parent::beginTransaction();
		}
		$this->exec('SAVEPOINT trans' . $this->transactionCounter);
		return $this->transactionCounter >= 0;
	}

	public function commit() {
		if (!--$this->transactionCounter) {
			return parent::commit();
		}
		return $this->transactionCounter >= 0;
	}

	public function rollback() {
		if (--$this->transactionCounter) {
			$this->exec('ROLLBACK TO trans' . $this->transactionCounter + 1);
			return true;
		}
		return parent::rollback();
	}

}

abstract class Soter_Database {

	private $driverType,
		$database,
		$tablePrefix,
		$pconnect,
		$debug,
		$charset,
		$collate,
		$tablePrefixSqlIdentifier,
		$masters,
		$slaves,
		$connectoinMasters,
		$connectoinSlaves;

	public function getDriverType() {
		return $this->driverType;
	}

	public function getMasters() {
		return $this->masters;
	}

	public function getMaster($key) {
		return $this->masters[$key];
	}

	public function getSlaves() {
		return $this->slaves;
	}

	public function getSlave($key) {
		return $this->slaves[$key];
	}

	public function getDatabase() {
		return $this->database;
	}

	public function getTablePrefix() {
		return $this->tablePrefix;
	}

	public function getPconnect() {
		return $this->pconnect;
	}

	public function getDebug() {
		return $this->debug;
	}

	public function getCharset() {
		return $this->charset;
	}

	public function getCollate() {
		return $this->collate;
	}

	public function getTablePrefixSqlIdentifier() {
		return $this->tablePrefixSqlIdentifier;
	}

	public function setDriverType($driverType) {
		$this->driverType = $driverType;
		return $this;
	}

	public function setMasters($masters) {
		$this->masters = $masters;
		return $this;
	}

	public function setSlaves($slaves) {
		$this->slaves = $slaves;
		return $this;
	}

	public function setDatabase($database) {
		$this->database = $database;
		return $this;
	}

	public function setTablePrefix($tablePrefix) {
		$this->tablePrefix = $tablePrefix;
		return $this;
	}

	public function setPconnect($pconnect) {
		$this->pconnect = $pconnect;
		return $this;
	}

	public function setDebug($debug) {
		$this->debug = $debug;
		return $this;
	}

	public function setCharset($charset) {
		$this->charset = $charset;
		return $this;
	}

	public function setCollate($collate) {
		$this->collate = $collate;
		return $this;
	}

	public function setTablePrefixSqlIdentifier($tablePrefixSqlIdentifier) {
		$this->tablePrefixSqlIdentifier = $tablePrefixSqlIdentifier;
		return $this;
	}

	public function getDefaultConfig() {
		return array(
		    'driverType' => 'Mysql',
		    'debug' => true,
		    'pconnect' => true,
		    'charset' => 'utf8',
		    'collate' => 'utf8_general_ci',
		    'database' => '',
		    'tablePrefix' => '',
		    'tablePrefixSqlIdentifier' => '_prefix_',
		    'masters' => array(
			'master01' => array(
			    'hostname' => '127.0.0.1',
			    'port' => 3306,
			    'username' => 'root',
			    'password' => '',
			)
		    ),
		    'slaves' => array()
		);
	}

	public function __construct(Array $config = array()) {
		$config = array_merge($this->getDefaultConfig(), $config);
		foreach (array_keys($config) as $key) {
			$this->{$key} = $config[$key];
		}
	}

	private function _init() {
		foreach ($this->getMasters() as $key => $config) {
			if (!isset($this->connectoinMasters[$key])) {
				$options[PDO::ATTR_PERSISTENT] = $this->getPconnect();
				if (strtolower($this->getDriverType()) == 'mysql') {
					$options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $this->getCharset() . ' COLLATE ' . $this->getCollate();
					$options[PDO::ATTR_EMULATE_PREPARES] = true;
					try {
						$dsn = 'mysql:host = ' . $config['hostname'] . ';port = ' . $config['port'] . ';dbname = ' . $this->getDatabase() . ';charset=' . $this->getCharset();
						$this->connectoinMasters[$key] = new Soter_PDO($dsn, $config['username'], $config['password'], $options);
						$this->connectoinSlaves[$key]->exec('SET NAMES ' . $this->getCharset());
					} catch (Exception $exc) {
						if ($this->getDebug()) {
							throw $exc;
						}
					}
				} elseif (strtolower($this->getDriverType()) == 'sqlite') {
					if (!file_exists($config['hostname'])) {
						throw new Soter_Exception_Database('sqlite3 database file [' . Sr::realPath($config['hostname']) . '] not found');
					}
					try {
						$this->connectoinMasters[$key] = new Soter_PDO('sqlite:' . $config['hostname'], null, null, $options);
					} catch (Exception $exc) {
						if ($this->getDebug()) {
							throw $exc;
						}
					}
				}
			}
		}
		$slaves = $this->getSlaves();
		if (empty($slaves)) {
			$this->connectoinSlaves[0] = current($this->connectoinMasters);
		} else {
			foreach ($slaves as $key => $config) {
				if (!isset($this->connectoinSlaves[$key])) {
					$options[PDO::ATTR_PERSISTENT] = $this->getPconnect();
					if (strtolower($this->getDriverType()) == 'mysql') {
						$options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $this->getCharset() . ' COLLATE ' . $this->getCollate();
						$options[PDO::ATTR_EMULATE_PREPARES] = true;
						try {
							$dsn = 'mysql:host = ' . $config['hostname'] . ';port = ' . $config['port'] . ';dbname = ' . $this->getDatabase() . ';charset=' . $this->getCharset();
							;
							$this->connectoinSlaves[$key] = new Soter_PDO($dsn, $config['username'], $config['password'], $options);
							$this->connectoinSlaves[$key]->exec('SET NAMES ' . $this->getCharset());
						} catch (Exception $exc) {
							if ($this->getDebug()) {
								throw $exc;
							}
						}
					} elseif (strtolower($this->getDriverType()) == 'sqlite') {
						if (!file_exists($config['hostname'])) {
							throw new Soter_Exception_Database('sqlite3 database file [' . Sr::realPath($config['hostname']) . '] not found');
						}
						try {
							$this->connectoinSlaves[$key] = new Soter_PDO('sqlite:' . $config['hostname'], null, null, $options);
						} catch (Exception $exc) {
							if ($this->getDebug()) {
								throw $exc;
							}
						}
					} else {
						throw new Soter_Exception_Database('unknown driverType [' . $this->getDriverType() . '] in database config');
					}
				}
			}
		}
	}

	public function query($sql, $values = null) {
		$this->_init();
	}

	public function update($sql, $values) {
		$this->_init();
	}

	public function updateBatch($key, $values) {
		$this->_init();
	}

	public function insertBatch($sql, $values = null) {
		$this->_init();
	}

	public function execute($sql) {
		$this->_init();
	}

	private function isWriteType($sql) {
		if (!preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql)) {
			return FALSE;
		}
		return TRUE;
	}

	private function displayError($message) {
		if ($this->getDebug() && Sr::config()->getShowError()) {
			throw new Soter_Exception_Database($message);
		}
	}

	public abstract function getSql();
}

class Soter_Database_ActiveRecord extends Soter_Database {

	private $arSelect
		, $arFrom
		, $arJoin
		, $arWhere
		, $arGroupby
		, $arHaving
		, $arLimit
		, $arOrderby
		, $_as
		, $_values

	;

	public function __construct(Array $config = array()) {
		parent::__construct($config);
		$this->_reset();
	}

	private function _reset() {
		$this->arSelect = '';
		$this->arFrom = array();
		$this->arJoin = array();
		$this->arWhere = array();
		$this->arGroupby = array();
		$this->arHaving = array();
		$this->arLimit = '';
		$this->arOrderby = array();
		$this->_as = array();
		$this->_values = array();
	}

	public function select($select) {
		$this->arSelect = $select;
		return $this;
	}

	public function from($from, $as = '') {
		$this->arFrom = array($from, $as);
		return $this;
	}

	public function join($table, $on, $type = '', $leftWrap = '', $rightWrap = '', $as = '') {
		$this->arJoin[] = array($table, $on, strtoupper($type), $leftWrap, $rightWrap, $as);
		return $this;
	}

	public function where($where, $leftWrap = ' AND ', $rightWrap = '') {
		$this->arWhere[] = array($where, $leftWrap, $rightWrap, count($this->arWhere));
		return $this;
	}

	public function _compileWhere($where, $leftWrap = ' AND ', $rightWrap = '', $index = -1) {
		$_where = '';
		if ($index == 0 && strtoupper($leftWrap) == ' AND ') {
			$leftWrap = '';
		}
		foreach ($where as $key => $value) {
			$_key = explode(' ', $key, 2);
			$op = count($_key) == 2 ? $_key[1] : '';
			$key = explode('.', $_key[0]);
			if (count($key) == 2) {
				$key = $this->_protectIdentifier($this->_checkPrefix($key[0])) . '.' . $this->_protectIdentifier($key[1]);
			} else {
				$key = $this->_protectIdentifier(current($key));
			}
			if (is_array($value)) {
				$op = $op ? $op . ' IN ' : ' IN ';
				$op = strtoupper($op);
				$_where[] = $key . ' ' . $op . '(' . implode(',', array_fill(0, count($value), '?')) . ')';
				foreach ($value as $v) {
					array_push($this->_values, $v);
				}
			} elseif (is_bool($value)) {
				$op = $op ? $op : '=';
				$op = strtoupper($op);
				$value = $value ? 1 : 0;
				$_where[] = $key . ' ' . $op . ' ? ';
				array_push($this->_values, $value);
			} elseif (is_null($value)) {
				$op = $op ? $op : 'IS';
				$op = strtoupper($op);
				$_where[] = $key . ' ' . $op . ' NULL ';
				array_push($this->_values, $value);
			} else {
				$op = $op ? $op : '=';
				$op = strtoupper($op);
				$_where[] = $key . ' ' . $op . ' ? ';
				array_push($this->_values, $value);
			}
		}
		return ' ' . $leftWrap . ' ' . implode(' AND ', $_where) . $rightWrap . ' ';
	}

	private function _compileSelect($select) {
		$selects = explode(',', $select);
		foreach ($selects as $key => $value) {
			$value = trim($value);
			$_info = explode('.', $value);
			if (count($_info) == 2) {
				$_info[0] = $this->_protectIdentifier($this->_checkPrefix($_info[0]));
				$_info[1] = $this->_protectIdentifier($_info[1]);
				$value = implode('.', $_info);
			} else {
				$value = $this->_protectIdentifier($value);
			}
			$selects[$key] = $value;
		}
		return implode(',', $selects);
	}

	private function _compileFrom($from, $as = '') {
		if ($as) {
			$this->_as[] = $as;
			$as = ' AS ' . $this->_protectIdentifier($as) . ' ';
		}
		return $this->_protectIdentifier($this->_checkPrefix($from)) . $as;
	}

	private function _compileJoin($table, $on, $type = '', $leftWrap = '', $rightWrap = '', $as = '') {
		if (is_array($table)) {
			$this->_as[] = current($table);
			$table = $this->_protectIdentifier($this->_checkPrefix(key($table))) . ' AS ' . $this->_protectIdentifier(current($table)) . ' ';
		} else {
			$table = $this->_protectIdentifier($this->_checkPrefix($table));
		}

		list($left, $right) = explode('=', $on);
		$_left = explode('.', $left);
		$_right = explode('.', $right);
		if (count($_left) == 2) {
			$_left[0] = $this->_protectIdentifier($this->_checkPrefix($_left[0]));
			$_left[1] = $this->_protectIdentifier($_left[1]);
			$left = ' ' . implode('.', $_left) . ' ';
		} else {
			$left = $this->_protectIdentifier($left);
		}

		if (count($_right) == 2) {
			$_right[0] = $this->_protectIdentifier($this->_checkPrefix($_right[0]));
			$_right[1] = $this->_protectIdentifier($_right[1]);
			$right = ' ' . implode('.', $_right) . ' ';
		} else {
			$right = $this->_protectIdentifier($right);
		}
		$on = $left . ' = ' . $right;
		if ($as) {
			$this->_as[] = $as;
			$as = ' AS ' . $this->_protectIdentifier($as);
		}
		return $leftWrap . ' ' . $type . ' JOIN ' . $table . ' ON ' . $on . ' ' . $rightWrap . ' ' . $as . ' ';
	}

	private function _checkPrefix($str) {
		$prefix = $this->getTablePrefix();
		if ($prefix && strpos($str, $prefix) === FALSE) {
			if (!in_array($str, $this->_as)) {
				return $prefix . $str;
			}
		}
		return $str;
	}

	private function _checkPrefixIdentifier($str) {
		$prefix = $this->getTablePrefix();
		$identifier = $this->getTablePrefixSqlIdentifier();
		return $identifier ? str_replace($identifier, $prefix, $str) : $str;
	}

	private function _protectIdentifier($str) {
		if (strtolower($this->getDriverType()) == 'mysql') {
			return "`$str`";
		}
		return $str;
	}

	public function getSql() {
		$firstJoin = array_shift($this->arJoin);
		$leftWrap = '';
		$_firstJoin = '';
		if (!empty($firstJoin)) {
			$leftWrap = $firstJoin[3];
			$_firstJoin = call_user_func_array(array($this, '_compileJoin'), $firstJoin);
		}
		$_firstJoin = $leftWrap ? substr(ltrim($_firstJoin), strlen($leftWrap)) : $_firstJoin;
		$table = $leftWrap . ' ' . call_user_func_array(array($this, '_compileFrom'), $this->arFrom) . ' ' . $_firstJoin;
		foreach ($this->arJoin as $join) {
			$table.=call_user_func_array(array($this, '_compileJoin'), $join);
		}
		$select = $this->_compileSelect($this->arSelect);
		$from = $table;
		$where = '';
		foreach ($this->arWhere as $w) {
			$where.=call_user_func_array(array($this, '_compileWhere'), $w);
		}
		$where = trim($where);
		if ($where) {
			$where = ' WHERE ' . $where;
		}
		$sql = 'SELECT ' . $select
			. ' FROM ' . $from
			. $where;
		return $sql;
	}

}
