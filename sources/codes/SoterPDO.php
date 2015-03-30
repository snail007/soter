<?php

/**
 * SoterPDO is simple and smart wrapper for PDO
 */
class Soter_PDO extends PDO {

	protected $transactionCount = 0;
	private $isLast;

	public function isInTransaction() {
		return !$this->isLast;
	}

	public function beginTransaction() {
		if (!$this->transactionCounter++) {
			return parent::beginTransaction();
		}
		$this->exec('SAVEPOINT trans' . $this->transactionCounter);
		return $this->transactionCounter >= 0;
	}

	public function commit() {
		if (!--$this->transactionCounter) {
			$this->isLast = true;
			return parent::commit();
		}
		$this->isLast = false;
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
		$slowQueryTime,
		$slowQueryHandle,
		$slowQueryDebug,
		$minIndexType,
		$indexDebug,
		$indexHandle,
		$masters,
		$slaves,
		$connectionMasters,
		$connectionSlaves,
		$_errorMsg,
		$_lastSql,
		$_isInTransaction = false,
		$_config,
		$_lastInsertId = 0,
		$_cacheTime = 0,
		$_cacheKey

	;

	public function lastId() {
		if (strtolower($this->getDriverType()) == 'sqlite') {
			//sqlite3的insertBatch是模拟的，
			//返回的最后插入id是这个批次最后一条记录的id，
			//而不是这个批次第一条记录的id，应该是这个批次第一条记录的id
			//这里通过计算得到这个批次第一条记录的id
			return $this->_lastInsertBatchCount > 1 ? ($this->_lastInsertId - $this->_lastInsertBatchCount + 1) : $this->_lastInsertId;
		} else {
			return $this->_lastInsertId;
		}
	}

	public function error() {
		return $this->_errorMsg;
	}

	public function lastSql() {
		return $this->_lastSql;
	}

	public function getSlowQueryDebug() {
		return $this->slowQueryDebug;
	}

	public function getMinIndexType() {
		return $this->minIndexType;
	}

	public function getIndexDebug() {
		return $this->indexDebug;
	}

	public function setSlowQueryDebug($slowQueryDebug) {
		$this->slowQueryDebug = $slowQueryDebug;
		return $this;
	}

	public function setMinIndexType($minIndexType) {
		$this->minIndexType = $minIndexType;
		return $this;
	}

	public function setIndexDebug($indexDebug) {
		$this->indexDebug = $indexDebug;
		return $this;
	}

	public function getSlowQueryTime() {
		return $this->slowQueryTime;
	}

	public function &getSlowQueryHandle() {
		return $this->slowQueryHandle;
	}

	public function &getIndexHandle() {
		return $this->indexHandle;
	}

	public function setSlowQueryTime($slowQueryTime) {
		$this->slowQueryTime = $slowQueryTime;
		return $this;
	}

	public function setSlowQueryHandle(Soter_Database_SlowQuery_Handle $slowQueryHandle) {
		$this->slowQueryHandle = $slowQueryHandle;
		return $this;
	}

	public function setIndexHandle(Soter_Database_Index_Handle $indexHandle) {
		$this->indexHandle = $indexHandle;
		return $this;
	}

	public function __construct(Array $config = array()) {
		$this->setConfig($config);
	}

	public function getConfig() {
		return $this->_config;
	}

	public function setConfig(Array $config = array()) {
		foreach (($this->_config = array_merge($this->getDefaultConfig(), $config)) as $key => $value) {
			$this->{$key} = $value;
		}
		$this->connectionMasters = array();
		$this->connectionSlaves = array();
		$this->_errorMsg = '';
		$this->_lastSql = '';
		$this->_isInTransaction = false;
		$this->_lastInsertId = 0;
		$this->_cacheKey = '';
		$this->_cacheTime = 0;
	}

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

	public static function getDefaultConfig() {
		return array(
		    'driverType' => 'mysql',
		    'debug' => true,
		    'pconnect' => false,
		    'charset' => 'utf8',
		    'collate' => 'utf8_general_ci',
		    'database' => '',
		    'tablePrefix' => '',
		    'tablePrefixSqlIdentifier' => '_prefix_',
		    //是否记录慢查询
		    'slowQueryDebug' => false,
		    'slowQueryTime' => 3000, //慢查询最小时间，单位毫秒，1秒=1000毫秒
		    'slowQueryHandle' => null,
		    //是否记录没有满足设置的索引类型的查询
		    'indexDebug' => true,
		    /**
		     * 索引使用的最小情况，只有小于最小情况的时候才会记录sql到日志
		     * minIndexType值从好到坏依次是:
		     * system > const > eq_ref > ref > fulltext > ref_or_null 
		     * > index_merge > unique_subquery > index_subquery > range 
		     * > index > ALL一般来说，得保证查询至少达到range级别，最好能达到ref
		     */
		    'minIndexType' => 'ALL',
		    'indexHandle' => null,
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

	private function _init() {
		$info = array(
		    'master' => array(
			'getMasters',
			'connectionMasters',
		    ),
		    'slave' => array(
			'getSlaves',
			'connectionSlaves',
		    ),
		);
		$slaves = $this->getSlaves();
		$masters = $this->getMasters();
		try {
			foreach ($info as $type => $group) {
				$configGroup = $this->{$group[0]}();
				$connections = &$this->{$group[1]};
				foreach ($configGroup as $key => $config) {
					if (!isset($connections[$key])) {
						$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$options[PDO::ATTR_PERSISTENT] = $this->getPconnect();
						if (strtolower($this->getDriverType()) == 'mysql') {
							$options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $this->getCharset() . ' COLLATE ' . $this->getCollate();
							$options[PDO::ATTR_EMULATE_PREPARES] = empty($slaves) && (count($masters) == 1);
							$dsn = 'mysql:host=' . $config['hostname'] . ';port=' . $config['port'] . ';dbname=' . $this->getDatabase() . ';charset=' . $this->getCharset();
							$connections[$key] = new Soter_PDO($dsn, $config['username'], $config['password'], $options);
							$connections[$key]->exec('SET NAMES ' . $this->getCharset());
						} elseif (strtolower($this->getDriverType()) == 'sqlite') {
							if (!file_exists($config['hostname'])) {
								$this->_displayError('sqlite3 database file [' . Sr::realPath($config['hostname']) . '] not found');
							}
							$connections[$key] = new Soter_PDO('sqlite:' . $config['hostname'], null, null, $options);
						}
					}
				}
			}
		} catch (Exception $e) {
			$this->_displayError($e);
		}
		if (empty($this->connectionSlaves) && !empty($this->connectionMasters)) {
			$this->connectionSlaves[0] = $this->connectionMasters[array_rand($this->connectionMasters)];
		}
		return !(empty($this->connectionMasters) && empty($this->connectionSlaves));
	}

	public function begin() {
		if (!$this->_init()) {
			return FALSE;
		}
		foreach ($this->connectionMasters as &$pdo) {
			$pdo->beginTransaction();
		}
		$this->_isInTransaction = TRUE;
	}

	public function commit() {
		if (!$this->_init()) {
			return FALSE;
		}
		foreach ($this->connectionMasters as &$pdo) {
			$pdo->commit();
			$this->_isInTransaction = !$pdo->isInTransaction();
		}
	}

	public function rollback() {
		if (!$this->_init()) {
			return FALSE;
		}
		foreach ($this->connectionMasters as &$pdo) {
			$pdo->rollback();
		}
	}

	public function cache($cacheTime, $cacheKey = '') {
		$this->_cacheTime = (int) $cacheTime;
		$this->_cacheKey = $cacheKey;
		return $this;
	}

	private function _checkPrefixIdentifier($str) {
		$prefix = $this->getTablePrefix();
		$identifier = $this->getTablePrefixSqlIdentifier();
		return $identifier && $prefix ? str_replace($identifier, $prefix, $str) : $str;
	}

	/**
	 * 执行一个sql语句，写入型的返回bool或者影响的行数（insert,delete,replace,update），搜索型的返回结果集
	 * @param type $sql       sql语句
	 * @param array $values   参数
	 * @return boolean|\Soter_Database_Resultset
	 */
	public function execute($sql = '', array $values = array()) {
		if (!$this->_init()) {
			return FALSE;
		}

		$startTime = Sr::microtime();
		$sql = $sql ? $this->_checkPrefixIdentifier($sql) : $this->getSql();
		$this->_lastSql = $sql;
		$values = !empty($values) ? $values : $this->_getValues();

		//读查询缓存
		$cacheHandle = null;
		if ($this->_cacheTime) {
			$cacheHandle = Sr::config()->getCacheHandle();
			if (empty($cacheHandle)) {
				throw new Soter_Exception_500('no cache handle found , please set cache handle');
			}
			$key = empty($this->_cacheKey) ? md5($sql . var_export($values, true)) : $this->_cacheKey;
			$return = $cacheHandle->get($key);
			if (!is_null($return)) {
				return $return;
			}
		}

		$isWriteType = $this->_isWriteType($sql);
		$isWritetRowsType = $this->_isWriteRowsType($sql);
		$isWriteInsertType = $this->_isWriteInsertType($sql);
		$return = false;
		try {
			//事务模式，使用主数据库组进行写和读
			if ($this->_isInTransaction) {
				$pdoArr = &$this->connectionMasters;
				foreach ($pdoArr as &$pdo) {
					if ($isWriteType) {
						if ($sth = $pdo->prepare($sql)) {
							$status = $sth->execute($values);
							$return = $isWritetRowsType ? $sth->rowCount() : $status;
							$this->_lastInsertId = $isWriteInsertType ? $pdo->lastInsertId() : 0;
						} else {
							$errorInfo = $sth->errorInfo();
							$this->_displayError($errorInfo[2], $errorInfo[1]);
						}
					} else {
						$key = array_rand($this->connectionSlaves);
						$pdo = &$this->connectionSlaves[$key];
						if ($sth = $pdo->prepare($sql)) {
							$return = $sth->execute($this->_getValues()) ? $sth->fetchAll(PDO::FETCH_ASSOC) : array();
							$return = new Soter_Database_Resultset($return);
						} else {
							$errorInfo = $sth->errorInfo();
							$this->_displayError($errorInfo[2], $errorInfo[1]);
						}
					}
				}
			} else {
				//非事务模式，使用主数据库组进行写，随机选择一个从数据库进行读
				if ($isWriteType) {
					$pdoArr = &$this->connectionMasters;
				} else {
					$pdoArr = &$this->connectionSlaves;
				}
				if ($isWriteType) {
					foreach ($pdoArr as &$pdo) {
						if ($sth = $pdo->prepare($sql)) {
							$status = $sth->execute($values);
							$return = $isWritetRowsType ? $sth->rowCount() : $status;
							$this->_lastInsertId = $isWriteInsertType ? $pdo->lastInsertId() : 0;
						} else {
							$errorInfo = $sth->errorInfo();
							$this->_displayError($errorInfo[2], $errorInfo[1]);
						}
					}
				} else {
					$key = array_rand($this->connectionSlaves);
					$pdo = &$this->connectionSlaves[$key];
					if ($sth = $pdo->prepare($sql)) {
						$return = $sth->execute($this->_getValues()) ? $sth->fetchAll(PDO::FETCH_ASSOC) : array();
						$return = new Soter_Database_Resultset($return);
					} else {
						$errorInfo = $sth->errorInfo();
						$this->_displayError($errorInfo[2], $errorInfo[1]);
					}
				}
			}
			//查询消耗的时间
			$usingTime = (Sr::microtime() - $startTime) . '';

			//explain查询
			$explainRows = array();
			if ($this->slowQueryDebug && $this->indexDebug) {
				$sth = $this->connectionMasters[0]->prepare('EXPLAIN ' . $sql);
				$sth->execute($this->_getValues());
				$explainRows = $sth->fetchAll(PDO::FETCH_ASSOC);
			}
			//慢查询记录
			if ($this->slowQueryDebug && ($usingTime >= $this->getSlowQueryTime())) {
				if ($this->slowQueryHandle instanceof Soter_Database_SlowQuery_Handle) {
					$this->slowQueryHandle->handle($sql, var_export($explainRows, true), $usingTime);
				}
			}
			//不满足索引条件的查询记录
			if ($this->indexDebug && $this->indexHandle instanceof Soter_Database_Index_Handle) {
				$badIndex = false;
				if (strtolower($this->getDriverType()) == 'mysql') {
					$order = array(
					    'system' => 1, 'const' => 2, 'eq_ref' => 3, 'ref' => 4,
					    'fulltext' => 5, 'ref_or_null' => 6, 'index_merge' => 7, 'unique_subquery' => 8,
					    'index_subquery' => 9, 'range' => 10, 'index' => 11, 'all' => 12,
					);
					foreach ($explainRows as $row) {
						if (isset($order[strtolower($row['type'])]) && isset($order[strtolower($this->getMinIndexType())])) {
							$key = $order[strtolower($row['type'])];
							$minKey = $order[strtolower($this->getMinIndexType())];
							if ($key > $minKey) {
								if (stripos($row['Extra'], 'optimized') === false) {
									$badIndex = true;
									break;
								}
							}
						}
					}
				} elseif (strtolower($this->getDriverType()) == 'sqlite') {
					
				}
				if ($badIndex) {
					$this->indexHandle->handle($sql, var_export($explainRows, true), $usingTime);
				}
			}
		} catch (Exception $exc) {
			$this->_reset();
			$this->_displayError($exc);
		}
		//写查询缓存
		if ($this->_cacheTime) {
			$key = empty($this->_cacheKey) ? md5($sql) : $this->_cacheKey;
			$cacheHandle->set($key, $return, $this->_cacheTime);
		}
		$this->_cacheKey = '';
		$this->_cacheTime = 0;
		$this->_reset();
		return $return;
	}

	private function _isWriteType($sql) {
		if (!preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql)) {
			return FALSE;
		}
		return TRUE;
	}

	private function _isWriteInsertType($sql) {
		if (!preg_match('/^\s*"?(INSERT|REPLACE)\s+/i', $sql)) {
			return FALSE;
		}
		return TRUE;
	}

	private function _isWriteRowsType($sql) {
		if (!preg_match('/^\s*"?(INSERT|UPDATE|DELETE|REPLACE)\s+/i', $sql)) {
			return FALSE;
		}
		return TRUE;
	}

	protected function _displayError($message, $code = 0) {
		$sql = $this->_lastSql ? ' , ' . "\n" . 'with query : ' . $this->_lastSql : '';
		if ($message instanceof Exception) {
			$this->_errorMsg = $message->getMessage() . $sql;
		} else {
			$this->_errorMsg = $message . $sql;
		}
		if ($this->getDebug() || $this->_isInTransaction) {
			if ($message instanceof Exception) {
				throw new Soter_Exception_Database($this->_errorMsg, 500);
			} else {

				throw new Soter_Exception_Database($message . $sql, $code);
			}
		}
	}

	public abstract function getSql();

	protected abstract function _getValues();
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
		, $arSet
		, $arUpdateBatch
		, $arInsert
		, $arInsertBatch
		, $_asTable
		, $_asColumn
		, $_values
		, $_sqlType
	;
	protected $_lastInsertBatchCount = 0

	;

	protected function _getValues() {
		return $this->_values;
	}

	public function __construct(Array $config = array()) {
		parent::__construct($config);
		$this->_reset();
	}

	protected function _reset() {
		$this->arSelect = array();
		$this->arFrom = array();
		$this->arJoin = array();
		$this->arWhere = array();
		$this->arGroupby = array();
		$this->arHaving = array();
		$this->arOrderby = array();
		$this->arLimit = '';
		$this->arSet = array();
		$this->arUpdateBatch = array();
		$this->arInsert = array();
		$this->arInsertBatch = array();
		$this->_asTable = array();
		$this->_asColumn = array();
		$this->_values = array();
		$this->_sqlType = 'select';
	}

	public function select($select) {
		foreach (explode(',', $select) as $key) {
			$this->arSelect[] = $key;
		}
		return $this;
	}

	public function from($from, $as = '') {
		$this->arFrom = array($from, $as);
		if ($as) {
			$this->_asTable[$as] = 1;
		}
		return $this;
	}

	public function join($table, $on, $type = '') {
		$this->arJoin[] = array($table, $on, strtoupper($type));
		return $this;
	}

	public function where($where, $leftWrap = 'AND', $rightWrap = '') {
		if (!empty($where) && is_array($where)) {
			$this->arWhere[] = array($where, $leftWrap, $rightWrap, count($this->arWhere));
		}
		return $this;
	}

	public function groupBy($key) {
		$key = explode(',', $key);
		foreach ($key as $k) {
			$this->arGroupby[] = $k;
		}
		return $this;
	}

	public function having(Array $having, $leftWrap = 'AND', $rightWrap = '') {
		$this->arHaving[] = array($having, $leftWrap, $rightWrap, count($this->arHaving));
		return $this;
	}

	public function orderBy($key, $type = 'desc') {
		$this->arOrderby[$key] = $type;
		return $this;
	}

	public function limit($offset, $count) {
		$this->arLimit = "$offset , $count";
		return $this;
	}

	public function insert($table, array $data) {
		$this->_sqlType = 'insert';
		$this->arInsert = $data;
		$this->_lastInsertBatchCount = 0;
		$this->from($table);
		return $this;
	}

	public function replace($table, array $data) {
		$this->_sqlType = 'replace';
		$this->arInsert = $data;
		$this->from($table);
		return $this;
	}

	private function _compileInsert() {
		$keys = array();
		$values = array();
		foreach ($this->arInsert as $key => $value) {
			$keys[] = $this->_protectIdentifier($key);
			$values[] = '?';
			$this->_values[] = $value;
		}
		if (!empty($keys)) {
			return '(' . implode(',', $keys) . ') ' . "\n" . 'VALUES (' . implode(',', $values) . ')';
		}
		return '';
	}

	public function insertBatch($table, array $data) {
		$this->_sqlType = 'insertBatch';
		$this->arInsertBatch = $data;
		$this->_lastInsertBatchCount = count($data);
		$this->from($table);
		return $this;
	}

	public function replaceBatch($table, array $data) {
		$this->_sqlType = 'replaceBatch';
		$this->arInsertBatch = $data;
		$this->from($table);
		return $this;
	}

	private function _compileInsertBatch() {
		$keys = array();
		$values = array();
		if (!empty($this->arInsertBatch[0])) {
			if (strtolower($this->getDriverType()) == 'mysql') {
				foreach ($this->arInsertBatch[0] as $key => $value) {
					$keys[] = $this->_protectIdentifier($key);
				}
				foreach ($this->arInsertBatch as $row) {
					$_values = array();
					foreach ($row as $key => $value) {
						$_values[] = '?';
						$this->_values[] = $value;
					}
					$values[] = '(' . implode(',', $_values) . ')';
				}
				return '(' . implode(',', $keys) . ') ' . "\n VALUES " . implode(' , ', $values);
			} elseif (strtolower($this->getDriverType()) == 'sqlite') {
				$_first = array();
				foreach ($this->arInsertBatch[0] as $key => $value) {
					$key = $this->_protectIdentifier($key);
					$keys[] = $key;
					$_first[] = ' ? as ' . $key;
					$this->_values[] = $value;
				}
				array_shift($this->arInsertBatch);
				$first = '(' . implode(',', $keys) . ')' . ' SELECT ' . implode(',', $_first) . "\n";
				foreach ($this->arInsertBatch as $row) {
					$_values = array();
					foreach ($row as $key => $value) {
						$_values[] = " ? ";
						$this->_values[] = $value;
					}
					$values[] = 'UNION SELECT ' . implode(',', $_values) . "\n";
				}
				return $first . implode(' ', $values);
			}
		}
		return '';
	}

	public function delete($table, array $where = array()) {
		$this->from($table);
		$this->where($where);
		$this->_sqlType = 'delete';
		return $this;
	}

	public function update($table, array $data = array(), array $where = array()) {
		$this->from($table);
		$this->where($where);
		foreach ($data as $key => $value) {
			if (is_bool($value)) {
				$this->set($key, (($value === FALSE) ? 0 : 1), true);
			} elseif (is_null($value)) {
				$this->set($key, 'NULL', false);
			} else {
				$this->set($key, $value, true);
			}
		}
		return $this;
	}

	/**
	 * 批量更新
	 * 
	 * @param array $values 必须包含$index字段
	 * @param string $index  唯一字段名称，一般是主键id
	 * @return int
	 */
	public function updateBatch($table, array $values, $index) {
		$this->from($table);
		$this->_sqlType = 'updateBatch';
		$this->arUpdateBatch = array($values, $index);
		if (!empty($values[0])) {
			foreach ($values as $val) {
				$ids[] = $val[$index];
			}
			$this->where(array($index => $ids));
		}
		return $this;
	}

	private function _compileUpdateBatch() {
		list($values, $index) = $this->arUpdateBatch;
		if (count($values) && isset($values[0][$index])) {
			$ids = array();
			$final = array();
			foreach ($values as $key => $val) {
				$ids[] = $val[$index];
				foreach (array_keys($val) as $field) {
					if ($field != $index) {
						$final[$field][] = 'WHEN ' . $this->_protectIdentifier($index) . ' = ' . $val[$index] . ' THEN ' . "?";
						$this->_values[] = $val[$field];
					}
				}
			}
			$sql = "";
			$cases = '';
			foreach ($final as $k => $v) {
				$cases .= $this->_protectIdentifier($k) . ' = CASE ' . "\n";
				foreach ($v as $row) {
					$cases .= $row . "\n";
				}
				$cases .= 'ELSE ' . $this->_protectIdentifier($k) . ' END, ';
			}
			$sql .= substr($cases, 0, -2);
			return $sql;
		}
		return '';
	}

	public function set($key, $value, $wrap = true) {
		$this->_sqlType = 'update';
		$this->arSet[$key] = array($value, $wrap);
		return $this;
	}

	/**
	 * 加表前缀，保护字段名和表名
	 * @param String $str 比如：user.id , id
	 * @return String
	 */
	public function wrap($str) {
		$_key = explode('.', $str);
		if (count($_key) == 2) {
			return $this->_protectIdentifier($this->_checkPrefix($_key[0])) . '.' . $this->_protectIdentifier($_key[1]);
		} else {
			return $this->_protectIdentifier($_key[0]);
		}
	}

	public function getSql() {
		switch ($this->_sqlType) {
			case 'select':
				return $this->_getSelectSql();
			case 'update':
				return $this->_getUpdateSql();
			case 'updateBatch':
				return $this->_getUpdateBatchSql();
			case 'insert':
				return $this->_getInsertSql();
			case 'insertBatch':
				return $this->_getInsertBatchSql();
			case 'replace':
				return $this->_getReplaceSql();
			case 'replaceBatch':
				return $this->_getReplaceBatchSql();
			case 'delete':
				return $this->_getDeleteSql();
		}
	}

	private function _getUpdateSql() {
		$sql[] = "\n" . 'UPDATE ';
		$sql[] = $this->_getFrom();
		$sql[] = "\n" . 'SET';
		$sql[] = $this->_compileSet();
		$sql[] = $this->_getWhere();
		$sql[] = $this->_getLimit();
		return implode(' ', $sql);
	}

	private function _getUpdateBatchSql() {
		$sql[] = "\n" . 'UPDATE ';
		$sql[] = $this->_getFrom();
		$sql[] = "\n" . 'SET';
		$sql[] = $this->_compileUpdateBatch();
		$sql[] = $this->_getWhere();
		return implode(' ', $sql);
	}

	private function _getInsertSql() {
		$sql[] = "\n" . 'INSERT INTO ';
		$sql[] = $this->_getFrom();
		$sql[] = $this->_compileInsert();
		return implode(' ', $sql);
	}

	private function _getInsertBatchSql() {
		$sql[] = "\n" . 'INSERT INTO ';
		$sql[] = $this->_getFrom();
		$sql[] = $this->_compileInsertBatch();
		return implode(' ', $sql);
	}

	private function _getReplaceSql() {
		$sql[] = "\n" . 'REPLACE INTO ';
		$sql[] = $this->_getFrom();
		$sql[] = $this->_compileInsert();
		return implode(' ', $sql);
	}

	private function _getReplaceBatchSql() {
		$sql[] = "\n" . 'REPLACE INTO ';
		$sql[] = $this->_getFrom();
		$sql[] = $this->_compileInsertBatch();
		return implode(' ', $sql);
	}

	private function _getDeleteSql() {
		$sql[] = "\n" . 'DELETE FROM ';
		$sql[] = $this->_getFrom();
		$sql[] = $this->_getWhere();
		return implode(' ', $sql);
	}

	private function _getSelectSql() {
		$select = $this->_compileSelect();
		$from = $this->_getFrom();
		$where = $this->_getWhere();
		$having = '';
		foreach ($this->arHaving as $w) {
			$having.=call_user_func_array(array($this, '_compileWhere'), $w);
		}
		$having = trim($having);
		if ($having) {
			$having = "\n" . ' HAVING ' . $having;
		}
		$groupBy = trim($this->_compileGroupBy());
		if ($groupBy) {
			$groupBy = "\n" . ' GROUP BY ' . $groupBy;
		}
		$orderBy = trim($this->_compileOrderBy());
		if ($orderBy) {
			$orderBy = "\n" . ' ORDER BY ' . $orderBy;
		}
		$limit = $this->_getLimit();
		$sql = "\n" . ' SELECT ' . $select
			. "\n" . ' FROM ' . $from
			. $where
			. $groupBy
			. $having
			. $orderBy
			. $limit
		;
		return $sql;
	}

	private function _compileSet() {
		$set = array();
		foreach ($this->arSet as $key => $value) {
			list($value, $wrap) = $value;
			if ($wrap) {
				$set[] = $this->_protectIdentifier($key) . ' = ' . '?';
				$this->_values[] = $value;
			} else {
				$set[] = $this->_protectIdentifier($key) . ' = ' . $value;
			}
		}
		return implode(' , ', $set);
	}

	private function _compileGroupBy() {
		$groupBy = array();
		foreach ($this->arGroupby as $key) {
			$_key = explode('.', $key);
			if (count($_key) == 2) {
				$groupBy[] = $this->_protectIdentifier($this->_checkPrefix($_key[0])) . '.' . $this->_protectIdentifier($_key[1]);
			} else {
				$groupBy[] = $this->_protectIdentifier($_key[0]);
			}
		}
		return implode(' , ', $groupBy);
	}

	private function _compileOrderBy() {
		$orderby = array();
		foreach ($this->arOrderby as $key => $type) {
			$type = strtoupper($type);
			$_key = explode('.', $key);
			if (count($_key) == 2) {
				$orderby[] = $this->_protectIdentifier($this->_checkPrefix($_key[0])) . '.' . $this->_protectIdentifier($_key[1]) . ' ' . $type;
			} else {
				$orderby[] = $this->_protectIdentifier($_key[0]) . ' ' . $type;
			}
		}
		return implode(' , ', $orderby);
	}

	private function _compileWhere($where, $leftWrap = 'AND', $rightWrap = '', $index = -1) {
		$_where = array();
		if ($index == 0 && strtoupper(trim($leftWrap)) == 'AND') {
			$leftWrap = '';
		}
		foreach ($where as $key => $value) {
			$key = trim($key);
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

	private function _compileSelect() {
		$selects = $this->arSelect;
		if (empty($selects)) {
			$selects[] = '*';
		}
		foreach ($selects as $key => $value) {
			$value = trim($value);
			if ($value != '*') {
				$_info = explode('.', $value);
				if (count($_info) == 2) {
					$_info[0] = $this->_protectIdentifier($this->_checkPrefix($_info[0]));
					$_info[1] = $this->_protectIdentifier($_info[1]);
					$value = implode('.', $_info);
				} else {
					$value = $this->_protectIdentifier($value);
				}
			}
			$selects[$key] = $value;
		}
		return implode(',', $selects);
	}

	private function _compileFrom($from, $as = '') {
		if ($as) {
			$this->_asTable[$as] = 1;
			$as = ' AS ' . $this->_protectIdentifier($as) . ' ';
		}
		return $this->_protectIdentifier($this->_checkPrefix($from)) . $as;
	}

	private function _compileJoin($table, $on, $type = '') {
		if (is_array($table)) {
			$this->_asTable[current($table)] = 1;
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
		return ' ' . $type . ' JOIN ' . $table . ' ON ' . $on . ' ';
	}

	private function _checkPrefix($str) {
		$prefix = $this->getTablePrefix();
		if ($prefix && strpos($str, $prefix) === FALSE) {
			if (!isset($this->_asTable[$str])) {
				return $prefix . $str;
			}
		}
		return $str;
	}

	private function _protectIdentifier($str) {
		if (stripos($str, '(')) {
			return $str;
		}
		$_str = explode(' ', $str);
		if (count($_str) == 3 && strtolower($_str[1]) == 'as') {
			return "`{$_str[0]}` AS `{$_str[2]}`";
		} else {
			return "`$str`";
		}
	}

	private function _getFrom() {
		$table = ' ' . call_user_func_array(array($this, '_compileFrom'), $this->arFrom) . ' ';
		foreach ($this->arJoin as $join) {
			$table.=call_user_func_array(array($this, '_compileJoin'), $join);
		}
		return $table;
	}

	private function _getWhere() {
		$where = '';
		//如果where中存在空的in，说明搜索条件一定是假，那么用0代表where假条件
		$hasEmptyIn = false;
		foreach ($this->arWhere as $w) {
			foreach ($w[0] as $value) {
				if (is_array($value) && empty($value)) {
					$hasEmptyIn = true;
					break;
				}
			}
			if ($hasEmptyIn) {
				break;
			}
			$where.=call_user_func_array(array($this, '_compileWhere'), $w);
		}
		if ($hasEmptyIn) {
			return '0';
		}
		$where = trim($where);
		if ($where) {
			$where = "\n" . ' WHERE ' . $where;
		}
		return $where;
	}

	private function _getLimit() {
		$limit = $this->arLimit;
		if ($limit) {
			$limit = "\n" . ' LIMIT ' . $limit;
		}
		return $limit;
	}

	public function __toString() {
		return $this->getSql();
	}

}

class Soter_Database_Resultset {

	private $_resultSet = array(),
		$_rowsKey = ''

	;

	public function __construct($resultSet) {
		$this->_resultSet = $resultSet;
	}

	public function total() {
		return count($this->_resultSet);
	}

	public function rows($isAssoc = true) {
		$key = $this->_rowsKey;
		$this->_rowsKey = '';
		if ($key) {
			if ($isAssoc) {
				$rows = array();
				foreach ($this->_resultSet as $row) {
					$rows[$row[$key]] = $row;
				}
				return $rows;
			} else {
				$rows = array();
				foreach ($this->_resultSet as $row) {
					$rows[$row[$key]] = array_values($row);
				}
				return $rows;
			}
		} else {
			if ($isAssoc) {
				return $this->_resultSet;
			} else {
				$rows = array();
				foreach ($this->_resultSet as $row) {
					$rows[] = array_values($row);
				}
				return $rows;
			}
		}
	}

	public function row($index = null, $isAssoc = true) {
		if (!is_null($index) && isset($this->_resultSet[$index])) {
			return $isAssoc ? $this->_resultSet[$index] : array_values($this->_resultSet[$index]);
		} else {
			$row = current($this->_resultSet);
			return $isAssoc ? $row : array_values($row);
		}
	}

	public function values($columnName = null) {
		$columns = array();
		foreach ($this->_resultSet as $row) {
			if (isset($row[$columnName])) {
				$columns[] = $row[$columnName];
			} else {
				return array();
			}
		}
		return $columns;
	}

	public function value($columnName = null, $default = null, $index = null) {
		$row = $this->row($index);
		return ($columnName && isset($row[$columnName])) ? $row[$columnName] : $default;
	}

	public function key($columnName) {
		$this->_rowsKey = $columnName;
		return $this;
	}

}
