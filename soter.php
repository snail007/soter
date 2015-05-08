<?php
/*
 * Copyright 2015 Soter(狂奔的蜗牛 672308444@163.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Soter
 *
 * An open source application development framework for PHP 5.3.0 or newer
 *
 * @package       Soter
 * @author        狂奔的蜗牛
 * @email         672308444@163.com
 * @copyright     Copyright (c) 2015 - 2015, 狂奔的蜗牛, Inc.
 * @link          http://git.oschina.net/snail/soter
 * @since         v1.0.0
 * @createdtime   2015-05-07 13:35:58
 */
 


/**
 * SoterPDO is simple and smart wrapper for PDO
 */
class Soter_PDO extends PDO {

	protected $transactionCounter = 0;
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
		$_lastPdoInstance,
		$_isInTransaction = false,
		$_config,
		$_lastInsertId = 0,
		$_cacheTime = 0,
		$_cacheKey,
		$_masterPdo = null,
		$_locked = false

	;

	public function __construct(Array $config = array()) {
		$this->setConfig($config);
	}

	public function &getLastPdoInstance() {
		return $this->_lastPdoInstance;
	}

	/**
	 * 锁定数据库连接，后面的读写都使用同一个主数据库连接
	 */
	public function lock() {
		$this->_locked = true;
		return $this;
	}

	/**
	 * 解锁数据库连接，后面的读写使用不同的数据库连接
	 */
	public function unlock() {
		$this->_locked = false;
		return $this;
	}

	/**
	 * 数据库连接是否处于锁定状态
	 * @return bool
	 */
	public function isLocked() {
		return $this->_locked;
	}

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
		$this->_lastPdoInstance = NULL;
		$this->_cacheKey = '';
		$this->_cacheTime = 0;
		$this->_masterPdo = '';
		$this->_locked = false;
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

	private function _isSqlite() {
		return strtolower($this->getDriverType()) == 'sqlite';
	}

	private function _isMysql() {
		return strtolower($this->getDriverType()) == 'mysql';
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
						if ($this->_isMysql()) {
							$options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $this->getCharset() . ' COLLATE ' . $this->getCollate();
							$options[PDO::ATTR_EMULATE_PREPARES] = TRUE; //empty($slaves) && (count($masters) == 1);
							$dsn = 'mysql:host=' . $config['hostname'] . ';port=' . $config['port'] . ';dbname=' . $this->getDatabase() . ';charset=' . $this->getCharset();
							$connections[$key] = new Soter_PDO($dsn, $config['username'], $config['password'], $options);
							$connections[$key]->exec('SET NAMES ' . $this->getCharset());
						} elseif ($this->_isSqlite()) {
							if (!file_exists($this->getDatabase())) {
								$this->_displayError('sqlite3 database file [' . Sr::realPath($this->getDatabase()) . '] not found');
							}
							$connections[$key] = new Soter_PDO('sqlite:' . $this->getDatabase(), null, null, $options);
						} else {
							throw new Soter_Exception_Database('unknown driverType [ ' . $this->getDriverType() . ' ]');
						}
					}
				}
			}
			if (empty($this->connectionSlaves) && !empty($this->connectionMasters)) {
				$this->connectionSlaves[0] = $this->connectionMasters[array_rand($this->connectionMasters)];
			}
			if (empty($this->_masterPdo) && !empty($this->connectionMasters)) {
				$this->_masterPdo = $this->connectionMasters[array_rand($this->connectionMasters)];
			}
			return !(empty($this->connectionMasters) && empty($this->connectionSlaves));
		} catch (Exception $e) {
			$this->_displayError($e);
		}
	}

	public function begin() {
		if (!$this->_init()) {
			return FALSE;
		}
		$this->_masterPdo->beginTransaction();
		$this->_isInTransaction = TRUE;
	}

	public function commit() {
		if (!$this->_init()) {
			return FALSE;
		}
		$this->_masterPdo->commit();
		$this->_isInTransaction = $this->_masterPdo->isInTransaction();
	}

	public function rollback() {
		if (!$this->_init()) {
			return FALSE;
		}
		$this->_masterPdo->rollback();
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
			if ($this->_isInTransaction) {
				//事务模式
				$pdo = &$this->_masterPdo; //使用一个固定的随机的主数据库，init方法里面被初始化一次
				$this->_lastPdoInstance = &$pdo;
				if ($sth = $pdo->prepare($sql)) {
					if ($isWriteType) {
						$status = $sth->execute($values);
						$return = $isWritetRowsType ? $sth->rowCount() : $status;
						$this->_lastInsertId = $isWriteInsertType ? $pdo->lastInsertId() : 0;
					} else {
						$return = $sth->execute($this->_getValues()) ? $sth->fetchAll(PDO::FETCH_ASSOC) : array();
						$return = new Soter_Database_Resultset($return);
					}
				} else {
					$errorInfo = $pdo->errorInfo();
					$this->_displayError($errorInfo[2], $errorInfo[1]);
				}
			} else {
				//非事务模式
				if ($this->isLocked()) {
					//锁定状态使用固定的一个主数据库
					$pdo = $this->_masterPdo;
				} else {
					//非锁定状态，使用随机选择一个主数据库进行写，随机选择一个从数据库进行读
					if ($isWriteType) {
						$pdo = &$this->connectionMasters[array_rand($this->connectionMasters)];
					} else {
						$pdo = &$this->connectionSlaves[array_rand($this->connectionSlaves)];
					}
				}
				$this->_lastPdoInstance = &$pdo;
				if ($sth = $pdo->prepare($sql)) {
					if ($isWriteType) {
						$status = $sth->execute($values);
						$return = $isWritetRowsType ? $sth->rowCount() : $status;
						$this->_lastInsertId = $isWriteInsertType ? $pdo->lastInsertId() : 0;
					} else {
						$return = $sth->execute($this->_getValues()) ? $sth->fetchAll(PDO::FETCH_ASSOC) : array();
						$return = new Soter_Database_Resultset($return);
					}
				} else {
					$errorInfo = $pdo->errorInfo();
					$this->_displayError($errorInfo[2], $errorInfo[1]);
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

	public function getSqlValues() {
		return $this->_getValues();
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
		, $_currentSql
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
		$this->_currentSql = '';
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

	public function having($having, $leftWrap = 'AND', $rightWrap = '') {
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
		$this->_lastInsertBatchCount = count($data);
		$this->from($table);
		return $this;
	}

	private function _compileInsertBatch() {
		$keys = array();
		$values = array();
		if (!empty($this->arInsertBatch[0])) {
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
		//在没有execute之前，防止多次调用导致values重复添加，这里在execute之前只编译一次，以后直接返回
		//execute之后$this->_currentSql会被_reset为空
		if ($this->_currentSql) {
			return $this->_currentSql;
		}
		switch ($this->_sqlType) {
			case 'select':
				$this->_currentSql = $this->_getSelectSql();
				break;
			case 'update':
				$this->_currentSql = $this->_getUpdateSql();
				break;
			case 'updateBatch':
				$this->_currentSql = $this->_getUpdateBatchSql();
				break;
			case 'insert':
				$this->_currentSql = $this->_getInsertSql();
				break;
			case 'insertBatch':
				$this->_currentSql = $this->_getInsertBatchSql();
				break;
			case 'replace':
				$this->_currentSql = $this->_getReplaceSql();
				break;
			case 'replaceBatch':
				$this->_currentSql = $this->_getReplaceBatchSql();
				break;
			case 'delete':
				$this->_currentSql = $this->_getDeleteSql();
				break;
		}
		return $this->_currentSql;
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
		if (is_string($where)) {
			return ' ' . $leftWrap . ' ' . $where . $rightWrap . ' ';
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
			return $isAssoc ? (is_array($row) ? $row : array()) : array_values($row);
		}
	}

	public function object($beanClassName, $index = null) {
		$beanDirName = Sr::config()->getBeanDirName();
		if (stripos($beanClassName, $beanDirName . '_') === false) {
			$beanClassName = $beanDirName . '_' . $beanClassName;
		}

		$object = new $beanClassName();
		if (!($object instanceof Soter_Bean)) {
			throw new Soter_Exception_500('error class [ ' . $beanClassName . ' ] , need instanceof Soter_Bean');
		}
		$row = $this->row($index);
		foreach ($row as $key => $value) {
			$method = "set" . ucfirst($key) . "";
			$object->{$method}($value);
		}
		return $object;
	}

	public function objects($beanClassName) {
		$beanDirName = Sr::config()->getBeanDirName();
		if (stripos($beanClassName, $beanDirName . '_') === false) {
			$beanClassName = $beanDirName . '_' . $beanClassName;
		}
		$object = new $beanClassName();
		if (!($object instanceof Soter_Bean)) {
			throw new Soter_Exception_500('error class [ ' . $beanClassName . ' ] , need instanceof Soter_Bean');
		}
		$objects = array();
		$rows = $this->rows();
		foreach ($rows as $row) {
			$object = new $beanClassName();
			foreach ($row as $key => $value) {
				$method = "set" . ucfirst($key);
				$object->{$method}($value);
			}
			$objects[] = $object;
		}
		return $objects;
	}

	public function values($columnName) {
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

	public function value($columnName, $default = null, $index = null) {
		$row = $this->row($index);
		return ($columnName && isset($row[$columnName])) ? $row[$columnName] : $default;
	}

	public function key($columnName) {
		$this->_rowsKey = $columnName;
		return $this;
	}

}


interface Soter_Logger_Writer {

	/**
	 * 这里不应该有输出，应该仅记录错误信息到日志系统（文件、数据库等等）<br/>
	 * 而且不能执行退出的操作比如exit，die
	 * @param Soter_Exception $exception
	 */
	public function write(Soter_Exception $exception);
}

interface Soter_Uri_Rewriter {

	/**
	 * 参数是uri中的访问路径部分 <br>
	 * 比如：http://127.0.0.1/index.php/Welcome/index.do?id=11<br>
	 * 参数就是后面的(Welcome/index.do)部分，也就是index.php/和?之间的部分<br>
	 * 这里应该返回处理后的uri，系统最终使用的就是这里返回的uri<br>
	 * @param String $uri
	 */
	public function rewrite($uri);
}

interface Soter_Exception_Handle {

	public function handle(Soter_Exception $exception);
}

interface Soter_Maintain_Handle {

	public function handle();
}

interface Soter_Database_SlowQuery_Handle {

	public function handle($sql, $explainString, $time);
}

interface Soter_Database_Index_Handle {

	public function handle($sql, $explainString, $time);
}

interface Soter_Cache {

	public function set($key, $value, $cacheTime);

	public function get($key);

	public function delete($key);

	public function clean();
}




abstract class Soter_Controller {
	
}

abstract class Soter_Model {
	
}

abstract class Soter_Dao {

	public abstract function getTable();

	public abstract function getPrimaryKey();

	public abstract function getColumns();
}

abstract class Soter_Business {
	
}

abstract class Soter_Bean {
	
}

abstract class Soter_Task {

	abstract function execute(Soter_CliArgs $args);
}

/**
 * @property Soter_Route $route
 */
abstract class Soter_Router {

	protected $route;

	public function __construct() {
		$this->route = new Soter_Route();
	}

	/**
	 * 
	 * @param Soter_Request $Soter_Request
	 * @return \Soter_Route
	 */
	public abstract function find();

	public function &route() {
		return $this->route;
	}

}

abstract class Soter_Exception extends Exception {

	protected $errorMessage, $errorCode, $errorFile, $errorLine, $errorType, $trace,
		$httpStatusLine = 'HTTP/1.0 500 Internal Server Error',
		$exceptionName = 'Soter_Exception';

	public function __construct($errorMessage = '', $errorCode = 0, $errorType = 'Exception', $errorFile = '', $errorLine = '0') {
		parent::__construct($errorMessage, $errorCode);
		$this->errorMessage = $errorMessage;
		$this->errorCode = $errorCode;
		$this->errorType = $errorType;
		$this->errorFile = Sr::realPath($errorFile);
		$this->errorLine = $errorLine;
		$this->trace = debug_backtrace(false);
	}

	public function errorType2string($errorType) {
		$value = $errorType;
		$levelNames = array(
		    E_ERROR => 'ERROR', E_WARNING => 'WARNING',
		    E_PARSE => 'PARSE', E_NOTICE => 'NOTICE',
		    E_CORE_ERROR => 'CORE_ERROR', E_CORE_WARNING => 'CORE_WARNING',
		    E_COMPILE_ERROR => 'COMPILE_ERROR', E_COMPILE_WARNING => 'COMPILE_WARNING',
		    E_USER_ERROR => 'USER_ERROR', E_USER_WARNING => 'USER_WARNING',
		    E_USER_NOTICE => 'USER_NOTICE');
		if (defined('E_STRICT')) {
			$levelNames[E_STRICT] = 'STRICT';
		}
		if (defined('E_DEPRECATED')) {
			$levelNames[E_DEPRECATED] = 'DEPRECATED';
		}
		if (defined('E_USER_DEPRECATED')) {
			$levelNames[E_USER_DEPRECATED] = 'USER_DEPRECATED';
		}
		if (defined('E_RECOVERABLE_ERROR')) {
			$levelNames[E_RECOVERABLE_ERROR] = 'RECOVERABLE_ERROR';
		}
		$levels = array();
		if (($value & E_ALL) == E_ALL) {
			$levels[] = 'E_ALL';
			$value&=~E_ALL;
		}
		foreach ($levelNames as $level => $name) {
			if (($value & $level) == $level) {
				$levels[] = $name;
			}
		}
		if (empty($levelNames[$this->errorCode])) {
			return $this->errorType ? $this->errorType : 'General Error';
		}
		return implode(' | ', $levels);
	}

	public function getErrorMessage() {
		return $this->errorMessage;
	}

	public function getErrorCode() {
		return $this->errorCode;
	}

	public function getErrorFile($safePath = FALSE) {
		return $safePath ? Sr::safePath($this->errorFile) : $this->errorFile;
	}

	public function getErrorLine() {
		return $this->errorLine;
	}

	public function getErrorType() {
		return $this->errorType2string($this->errorCode);
	}

	public function render($isJson = FALSE, $return = FALSE) {
		if ($isJson) {
			$string = $this->renderJson();
		} elseif (Sr::isCli()) {
			$string = $this->renderCli();
		} else {
			$string = str_replace('</body>', $this->getTraceString(FALSE) . '</body>', $this->renderHtml());
		}
		if ($return) {
			return $string;
		} else {
			echo $string;
		}
	}

	public function getTraceCliString() {
		return $this->getTraceString(TRUE);
	}

	public function getTraceHtmlString() {
		return $this->getTraceString(FALSE);
	}

	private function getTraceString($isCli) {
		$trace = $this->trace;
		array_shift($trace);
		$trace = array_reverse($trace);
		$str = $isCli ? "[ Debug Backtrace ]\n" : '<div style="padding:10px;">[ Debug Backtrace ]<br/>';
		foreach ($trace as $e) {
			array_shift($trace);
			if (Sr::arrayGet($e, 'function') == 'call_user_func_array') {
				break;
			}
		}
		if (empty($trace)) {
			return '';
		}
		foreach ($trace as $e) {
			if (!empty($e['class']) && stripos($e['class'], 'Soter_') === 0) {
				break;
			}
			$file = Sr::safePath(Sr::arrayGet($e, 'file'));
			$line = Sr::arrayGet($e, 'line');
			$func = (!empty($e['class']) ? "{$e['class']}{$e['type']}{$e['function']}()" : "{$e['function']}()");
			$str.="&rarr; {$func} " . ($line ? "[ line:{$line} {$file} ]" : '') . ($isCli ? "\n" : '<br/>');
		}
		$str.=$isCli ? "\n" : '</div>';
		return $str;
	}

	public function renderCli() {
		return "$this->exceptionName [ " . $this->getErrorType() . " ]\n"
			. "Line: " . $this->getErrorLine() . ". " . $this->getErrorFile() . "\n"
			. "Message: " . $this->getErrorMessage() . "\n"
			. "Time: " . date('Y/m/d H:i:s T') . "\n";
	}

	public function renderHtml() {
		return '<body style="padding:0;margin:0;background:black;color:whitesmoke;">'
			. '<div style="padding:10px;background:red;font-size:18px;">' . $this->exceptionName . ' [ ' . $this->getErrorType() . ' ] </div>'
			. '<div style="padding:10px;background:black;font-size:14px;color:yellow;line-height:1.5em;">'
			. '<font color="whitesmoke">Line: </font>' . $this->getErrorLine() . ' [ ' . $this->getErrorFile(TRUE) . ' ]<br/>'
			. '<font color="whitesmoke">Message: </font>' . htmlspecialchars($this->getErrorMessage()) . '</br>'
			. '<font color="whitesmoke">Time: </font>' . date('Y/m/d H:i:s T') . '</div>'
			. '</body>';
	}

	public function renderJson() {
		$config = soter::getConfig();
		$json[$config->getExcptionErrorJsonFileName()] = $this->getErrorFile();
		$json[$config->getExcptionErrorJsonLineName()] = $this->getErrorLine();
		$json[$config->getExcptionErrorJsonMessageName()] = $this->getErrorMessage();
		$json[$config->getExcptionErrorJsonTypeName()] = $this->getErrorType();
		$json[$config->getExcptionErrorJsonCodeName()] = $this->getErrorCode();
		$json[$config->getExcptionErrorJsonTimeName()] = date('Y/m/d H:i:s T');
		$json[$config->getExcptionErrorJsonTraceName()] = $this->getTraceCliString();
		$output = json_encode($json);
		return $output;
	}

	public function setHttpHeader() {
		header($this->httpStatusLine);
		return $this;
	}

	public function __toString() {
		return $this->render(FALSE, TRUE);
	}

}

abstract class Soter_Session {

	protected $config;

	public function __construct($configFileName) {
		if (is_array($configFileName)) {
			$this->config = $configFileName;
		} else {
			$this->config = Sr::config($configFileName);
		}
	}

	public abstract function init();
}


class Soter_Exception_404 extends Soter_Exception {

	protected $exceptionName = 'Soter_Exception_404',
			$httpStatusLine = 'HTTP/1.0 404 Not Found';
}

class Soter_Exception_500 extends Soter_Exception {

	protected $exceptionName = 'Soter_Exception_500',
			$httpStatusLine = 'HTTP/1.0 500 Internal Server Error';

}

class Soter_Exception_Database extends Soter_Exception {

	protected $exceptionName = 'Soter_Exception_Database',
			$httpStatusLine = 'HTTP/1.0 500 Internal Server Error';

}


class Soter_Request {

	private $uri;

	public function __construct($uri = '') {
		$this->setUri($uri);
	}

	public function setUri($uri) {
		$this->uri = $uri;
		return $this;
	}

	public function getUri() {
		return $this->uri;
	}

}

class Soter_View {

	private static $vars = array();

	public function add($key, $value = array()) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				if (!isset(self::$vars[$k])) {
					self::$vars[$k] = $v;
				}
			}
		} else {
			if (!isset(self::$vars[$key])) {
				self::$vars[$key] = $value;
			}
		}
		return $this;
	}

	public function set($key, $value = array()) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				self::$vars[$k] = $v;
			}
		} else {
			self::$vars[$key] = $value;
		}
		return $this;
	}

	private function _load($path, $data = array(), $return = false) {
		if (!file_exists($path)) {
			throw new Soter_Exception_500('view file : [ ' . $path . ' ] not found');
		}
		$data = array_merge(self::$vars, $data);
		if (!empty($data)) {
			extract($data);
		}
		if ($return) {
			@ob_start();
			include $path;
			$html = ob_get_contents();
			@ob_end_clean();
			return $html;
		} else {
			include $path;
			return;
		}
	}

	/**
	 * 加载一个视图<br/>
	 * @param string $viewName 视图名称
	 * @param array  $data     视图中可以使用的数据
	 * @param bool   $return   是否返回视图内容
	 * @return string
	 */
	public function load($viewName, $data = array(), $return = false) {
		$config = Sr::config();
		$path = $config->getApplicationDir() . $config->getViewsDirName() . '/' . $viewName . '.php';
		$hmvcModules = $config->getHmvcModules();
		$hmvcDirName = Sr::arrayGet($hmvcModules, $config->getRoute()->getHmvcModuleName(), '');
		//当load方法在主项目的视图中被调用，然后hmvc主项目load了这个视图，那么这个视图里面的load应该使用的是主项目视图。
		//hmvc访问
		if ($hmvcDirName) {
			$trace = debug_backtrace();
			$calledFilePath = array_shift($trace);
			$calledFilePath = Sr::realPath(Sr::arrayGet($calledFilePath, 'file'));
			$hmvcPath = $config->getPrimaryApplicationDir() . $config->getHmvcDirName() . '/' . $hmvcDirName;
			$calledIsInHmvc = $calledFilePath && $hmvcDirName && (strpos($calledFilePath, $hmvcPath) === 0);
			//发现load是在主项目中被调用的，使用主项目视图
			if (!$calledIsInHmvc) {
				$path = $config->getPrimaryApplicationDir() . $config->getViewsDirName() . '/' . $viewName . '.php';
			}
		}
		return $this->_load($path, $data, $return);
	}

	/**
	 * 加载主项目的视图<br/>
	 * 这个一般是在hmvc模块中使用到，用于复用主项目的视图文件，比如通用的header等。<br/>
	 * @param string $viewName 主项目视图名称
	 * @param array  $data     视图中可以使用的数据
	 * @param bool   $return   是否返回视图内容
	 * @return string
	 */
	public function loadParent($viewName, $data = array(), $return = false) {
		$config = Sr::config();
		$path = $config->getPrimaryApplicationDir() . $config->getViewsDirName() . '/' . $viewName . '.php';
		return $this->_load($path, $data, $return);
	}

}

class Soter_Response {

	public function render() {
		
	}

}

class Soter_CliArgs {

	private $args;

	public function __construct() {
		$this->args = Sr::getOpt();
	}

	public function get($key = null, $default = null) {
		if (empty($key)) {
			return $this->args;
		}
		return Sr::arrayGet($this->args, $key, $default);
	}

}

class Soter_Route {

	private $found = false;
	private $controller, $method, $args, $hmvcModuleName;

	public function getHmvcModuleName() {
		return $this->hmvcModuleName;
	}

	public function setHmvcModuleName($hmvcModuleName) {
		$this->hmvcModuleName = $hmvcModuleName;
		return $this;
	}

	public function found() {
		return $this->found;
	}

	public function setFound($found) {
		$this->found = $found;
		return $this;
	}

	public function getController() {
		return $this->controller;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getArgs() {
		return $this->args;
	}

	public function __construct() {
		$this->args = array();
	}

	public function setController($controller) {
		$this->controller = $controller;
		return $this;
	}

	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}

	public function setArgs(array $args) {
		$this->args = $args;
		return $this;
	}

}

class Soter_Default_Router_Get extends Soter_Router {

	public function find() {
		$config = Sr::config();
		$uri = explode('?', $config->getRequest()->getUri());
		$query = end($uri);
		parse_str($query, $get);
		$controllerName = Sr::arrayGet($get, $config->getRouterUrlControllerKey(), '');
		$methodName = Sr::arrayGet($get, $config->getRouterUrlMethodKey(), '');
		$hmvcModuleName = Sr::arrayGet($get, $config->getRouterUrlModuleKey(), '');
		//hmvc检测
		$hmvcModuleDirName = Soter::checkHmvc($hmvcModuleName, false);
		if ($controllerName) {
			$controllerName = $config->getControllerDirName() . '_' . $controllerName;
		}
		if ($methodName) {
			$methodName = $config->getMethodPrefix() . $methodName;
		}
		return $this->route->setHmvcModuleName($hmvcModuleName)
				->setController($controllerName)
				->setMethod($methodName)
				->setFound($hmvcModuleDirName || $controllerName);
	}

}

class Soter_Default_Router_PathInfo extends Soter_Router {

	/**
	 * 只处理pathinfo模式的路由<br>
	 * 比如：<br>
	 * uri：/index.php/Vip uri至少有一个hmvc模块名称Vip，或者控制器名称Vip<br>
	 * 如果没有就认为不是pathinfo模式的路由<br>
	 * @return \Soter_Route
	 */
	public function find() {
		$config = Soter::getConfig();
		//获取uri
		$uri = $config->getRequest()->getUri();

		/**
		 * pathinfo模式路由判断以及解析uri中的访问路径 
		 * 比如：http://127.0.0.1/index.php/Welcome/index.do?id=11
		 * 获取的是后面的(Welcome/index.do)部分，也就是index.php/和?之间的部分
		 */
		$indexName = Soter::getConfig()->getIndexName();
		if (($pos = stripos($uri, '/' . $indexName)) !== FALSE) {
			$uri = ltrim(substr($uri, $pos + strlen('/' . $indexName)), '/');
			$_uriarr = explode('?', $uri);
			$uri = trim(current($_uriarr), '/');
			if ($uriRewriter = $config->getUriRewriter()) {
				$uri = $uriRewriter->rewrite($uri);
			}
		} else {
			$uri = '';
		}
		if (empty($uri)) {
			//没有找到hmvc模块名称，或者控制器名称
			return $this->route->setFound(FALSE);
		}
		//到此$uri形如：Welcome/index.do , Welcome/User , Welcome
		$_info = explode('/', $uri);
		$hmvcModule = current($_info);
		//hmvc检测 ，Soter::checkHmvc()执行后，主配置会被hmvc子项目配置覆盖
		if ($hmvcModuleDirName = Soter::checkHmvc($hmvcModule, FALSE)) {
			//找到hmvc模块,去除hmvc模块名称，得到真正的路径
			$uri = ltrim(substr($uri, strlen($hmvcModule)), '/');
		}
		//首先控制器名和方法名初始化为默认
		$controller = $config->getDefaultController();
		$method = $config->getDefaultMethod();
		$subfix = $config->getMethodUriSubfix();

		/**
		 * 到此，如果上面$uri被去除掉hmvc模块名称后，$uri有可能是空
		 * 或者$uri有控制器名称或者方法-参数名称
		 * 形如：1.Welcome/article-001.do , 2.Welcome/article-001.do , 
		 *      3.article-001.do ,4.article.do , 5.Welcome/User , 6.Welcome 
		 */
		if ($uri) {
			//解析路径
			$methodPathArr = explode($subfix, $uri);
			//找到了控制器名或者方法-参数名(1,2,3,4)
			if (Sr::strEndsWith($uri, $subfix)) {
				//找到了控制器名和方法-参数名(1,2)，覆盖上面的默认控制器名和方法-参数名
				if (stripos($methodPathArr[0], '/') !== false) {
					$controller = str_replace('/', '_', dirname($uri));
					$method = basename($methodPathArr[0]);
				} else {
					//只找到了方法-参数名(3,4)，覆盖上面的默认方法名
					$method = basename($methodPathArr[0]);
				}
			} else {
				//只找到了控制器名(5,6)，覆盖上面的默认控制器名
				$controller = str_replace('/', '_', $uri);
			}
		}
		$controller = $config->getControllerDirName() . '_' . $controller;
		//统一解析方法-参数名
		$methodAndParameters = explode($config->getMethodParametersDelimiter(), $method);
		$method = $config->getMethodPrefix() . current($methodAndParameters);
		array_shift($methodAndParameters);
		$parameters = $methodAndParameters;
		//$config->getMethodPrefix() . $method;
		return $this->route
				->setHmvcModuleName($hmvcModule)
				->setController($controller)
				->setMethod($method)
				->setArgs($parameters)
				->setFound(TRUE);
	}

}

/**
 * @property Soter_Exception_Handle $exceptionHandle
 */
class Soter_Config {

	private $applicationDir = '', //项目目录
		$primaryApplicationDir = '', //主项目目录
		$indexDir = '', //入口文件目录
		$indexName = '', //入口文件名称
		$classesDirName = 'classes',
		$hmvcDirName = 'hmvc',
		$libraryDirName = 'library',
		$functionsDirName = 'functions',
		$viewsDirName = 'views',
		$configDirName = 'config',
		$configTestingDirName = 'testing',
		$configProductionDirName = 'production',
		$configDevelopmentDirName = 'development',
		$controllerDirName = 'Controller',
		$businessDirName = 'Business',
		$daoDirName = 'Dao',
		$beanDirName = 'Bean',
		$modelDirName = 'Model',
		$taskDirName = 'Task',
		$defaultController = 'Welcome',
		$defaultMethod = 'index',
		$methodPrefix = 'do_',
		$methodUriSubfix = '.do',
		$routerUrlModuleKey = 'm',
		$routerUrlControllerKey = 'c',
		$routerUrlMethodKey = 'a',
		$methodParametersDelimiter = '-',
		$logsSubDirNameFormat = 'Y-m-d/H',
		$cookiePrefix = '',
		$backendServerIpWhitelist = '',
		$isRewrite = FALSE,
		$request, $showError = true,
		$excptionErrorJsonMessageName = 'message',
		$excptionErrorJsonFileName = 'file',
		$excptionErrorJsonLineName = 'line',
		$excptionErrorJsonTypeName = 'type',
		$excptionErrorJsonCodeName = 'code',
		$excptionErrorJsonTraceName = 'trace',
		$excptionErrorJsonTimeName = 'time',
		$routersContainer = array(),
		$packageMasterContainer = array(),
		$packageContainer = array(),
		$loggerWriterContainer = array(),
		$uriRewriter,
		$exceptionHandle, $route, $environment = Sr::ENV_DEVELOPMENT,
		$serverEnvironmentTestingValue = 'testing',
		$serverEnvironmentDevelopmentValue = 'development',
		$serverEnvironmentProductionValue = 'production',
		$hmvcModules = array(),
		$isMaintainMode = false,
		$maintainIpWhitelist = array(),
		$maintainModeHandle,
		$databseConfig,
		$cacheHandle,
		$sessionConfig,
		$sessionHandle,
		$methodCacheConfig,
		$dataCheckRules

	;

	public function getDataCheckRules() {
		return $this->dataCheckRules;
	}

	public function setDataCheckRules($dataCheckRules) {
		$this->dataCheckRules = is_array($dataCheckRules) ? $dataCheckRules : Sr::config($dataCheckRules);
		return $this;
	}

	public function getMethodCacheConfig() {
		return $this->methodCacheConfig;
	}

	public function setMethodCacheConfig($methodCacheConfig) {
		$this->methodCacheConfig = is_array($methodCacheConfig) ? $methodCacheConfig : Sr::config($methodCacheConfig);
		return $this;
	}

	public function getViewsDirName() {
		return $this->viewsDirName;
	}

	public function setViewsDirName($viewsDirName) {
		$this->viewsDirName = $viewsDirName;
		return $this;
	}

	/**
	 * 
	 * @return Soter_Cache
	 */
	public function getCacheHandle() {
		return $this->cacheHandle;
	}

	public function setCacheHandle($cacheHandle) {
		if ($cacheHandle instanceof Soter_Cache) {
			$this->cacheHandle = $cacheHandle;
		} else {
			$this->cacheHandle = Sr::config($cacheHandle);
		}
		return $this;
	}

	/**
	 * 
	 * @return Soter_Session
	 */
	public function getSessionHandle() {
		return $this->sessionHandle;
	}

	public function setSessionHandle($sessionHandle) {

		if ($sessionHandle instanceof Soter_Session) {
			$this->sessionHandle = $sessionHandle;
		} else {
			$this->sessionHandle = Sr::config($sessionHandle);
		}
		return $this;
	}

	public function getSessionConfig() {
		if (empty($this->sessionConfig)) {
			$this->sessionConfig = array(
			    'autostart' => false,
			    'cookie_path' => '/',
			    'cookie_domain' => Sr::server('HTTP_HOST'),
			    'session_name' => 'SOTER',
			    'lifetime' => 3600,
			);
		}
		return $this->sessionConfig;
	}

	public function setSessionConfig($sessionConfig) {
		if (is_array($sessionConfig)) {
			$this->sessionConfig = $sessionConfig;
		} else {
			$this->sessionConfig = Sr::config($sessionConfig);
		}
		return $this;
	}

	public function getDatabseConfig($group = null) {
		if (empty($group)) {
			return $this->databseConfig;
		} else {
			return isset($this->databseConfig[$group]) ? $this->databseConfig[$group] : array();
		}
	}

	public function setDatabseConfig($databseConfig) {
		$this->databseConfig = is_array($databseConfig) ? $databseConfig : Sr::config($databseConfig);
		return $this;
	}

	public function getIsMaintainMode() {
		return $this->isMaintainMode;
	}

	public function getMaintainModeHandle() {
		return $this->maintainModeHandle;
	}

	public function setIsMaintainMode($isMaintainMode) {
		$this->isMaintainMode = $isMaintainMode;
		return $this;
	}

	public function setMaintainModeHandle(Soter_Maintain_Handle $maintainModeHandle) {
		$this->maintainModeHandle = $maintainModeHandle;
		return $this;
	}

	public function getMaintainIpWhitelist() {
		return $this->maintainIpWhitelist;
	}

	public function setMaintainIpWhitelist($maintainIpWhitelist) {
		$this->maintainIpWhitelist = $maintainIpWhitelist;
		return $this;
	}

	public function getMethodParametersDelimiter() {
		return $this->methodParametersDelimiter;
	}

	public function setMethodParametersDelimiter($methodParametersDelimiter) {
		$this->methodParametersDelimiter = $methodParametersDelimiter;
		return $this;
	}

	public function getRouterUrlModuleKey() {
		return $this->routerUrlModuleKey;
	}

	public function getRouterUrlControllerKey() {
		return $this->routerUrlControllerKey;
	}

	public function getRouterUrlMethodKey() {
		return $this->routerUrlMethodKey;
	}

	public function setRouterUrlModuleKey($routerUrlModuleKey) {
		$this->routerUrlModuleKey = $routerUrlModuleKey;
		return $this;
	}

	public function setRouterUrlControllerKey($routerUrlControllerKey) {
		$this->routerUrlControllerKey = $routerUrlControllerKey;
		return $this;
	}

	public function setRouterUrlMethodKey($routerUrlMethodKey) {
		$this->routerUrlMethodKey = $routerUrlMethodKey;
		return $this;
	}

	/**
	 * 
	 * @return Soter_Uri_Rewriter
	 */
	public function getUriRewriter() {
		return $this->uriRewriter;
	}

	public function setUriRewriter(Soter_Uri_Rewriter $uriRewriter) {
		$this->uriRewriter = $uriRewriter;
		return $this;
	}

	public function getPrimaryApplicationDir() {
		return $this->primaryApplicationDir;
	}

	public function setPrimaryApplicationDir($primaryApplicationDir) {
		$this->primaryApplicationDir = Sr::realPath($primaryApplicationDir) . '/';
		return $this;
	}

	public function getBackendServerIpWhitelist() {
		return $this->backendServerIpWhitelist;
	}

	/**
	 * 如果服务器是ngix之类代理转发请求到后端apache运行的PHP<br>
	 * 那么这里应该设置信任的nginx所在服务器的ip<br>
	 * nginx里面应该设置 X_FORWARDED_FOR server变量来表示真实的客户端IP<br>
	 * 不然通过Sr::clientIp()是获取不到真实的客户端IP的<br>
	 * @param type $backendServerIpWhitelist
	 * @return \Soter_Config
	 */
	public function setBackendServerIpWhitelist(Array $backendServerIpWhitelist) {
		$this->backendServerIpWhitelist = $backendServerIpWhitelist;
		return $this;
	}

	public function getCookiePrefix() {
		return $this->cookiePrefix;
	}

	public function setCookiePrefix($cookiePrefix) {
		$this->cookiePrefix = $cookiePrefix;
		return $this;
	}

	public function getLogsSubDirNameFormat() {
		return $this->logsSubDirNameFormat;
	}

	/**
	 * 设置日志子目录格式，参数就是date()函数的第一个参数,默认是 Y-m-d/H
	 * @param type $logsSubDirNameFormat
	 * @return \Soter_Config
	 */
	public function setLogsSubDirNameFormat($logsSubDirNameFormat) {
		$this->logsSubDirNameFormat = $logsSubDirNameFormat;
		return $this;
	}

	public function addAutoloadFunctions(Array $funciontsFileNameArray) {
		foreach ($funciontsFileNameArray as $functionsFileName) {
			Sr::functions($functionsFileName);
		}
		return $this;
	}

	public function getFunctionsDirName() {
		return $this->functionsDirName;
	}

	public function setFunctionsDirName($functionsDirName) {
		$this->functionsDirName = $functionsDirName;
		return $this;
	}

	public function getModelDirName() {
		return $this->modelDirName;
	}

	public function setModelDirName($modelDirName) {
		$this->modelDirName = $modelDirName;
		return $this;
	}

	public function getBeanDirName() {
		return $this->beanDirName;
	}

	public function setBeanDirName($beanDirName) {
		$this->beanDirName = $beanDirName;
		return $this;
	}

	public function getBusinessDirName() {
		return $this->businessDirName;
	}

	public function getDaoDirName() {
		return $this->daoDirName;
	}

	public function getTaskDirName() {
		return $this->taskDirName;
	}

	public function setBusinessDirName($businessDirName) {
		$this->businessDirName = $businessDirName;
		return $this;
	}

	public function setDaoDirName($daoDirName) {
		$this->daoDirName = $daoDirName;
		return $this;
	}

	public function setTaskDirName($taskDirName) {
		$this->taskDirName = $taskDirName;
		return $this;
	}

	public function getServerEnvironment($environment) {
		switch (strtoupper($environment)) {
			case strtoupper($this->getServerEnvironmentDevelopmentValue()):
				return Sr::ENV_DEVELOPMENT;
			case strtoupper($this->getServerEnvironmentProductionValue()):
				return Sr::ENV_PRODUCTION;
			case strtoupper($this->getServerEnvironmentTestingValue()):
				return Sr::ENV_TESTING;
			default:
				throw new Soter_Exception_500('wrong parameter value[' . $environment . '] of getServerEnvironment(), '
				. 'should be one of [' . $this->getServerEnvironmentDevelopmentValue() . ',' .
				$this->getServerEnvironmentTestingValue() . ',' .
				$this->getServerEnvironmentProductionValue() . ']');
		}
	}

	public function getServerEnvironmentTestingValue() {
		return $this->serverEnvironmentTestingValue;
	}

	public function getServerEnvironmentProductionValue() {
		return $this->serverEnvironmentProductionValue;
	}

	public function getServerEnvironmentDevelopmentValue() {
		return $this->serverEnvironmentDevelopmentValue;
	}

	public function setServerEnvironmentDevelopmentValue($serverEnvironmentDevelopmentValue) {
		$this->serverEnvironmentDevelopmentValue = $serverEnvironmentDevelopmentValue;
		return $this;
	}

	public function setServerEnvironmentTestingValue($serverEnvironmentTestingValue) {
		$this->serverEnvironmentTestingValue = $serverEnvironmentTestingValue;
		return $this;
	}

	public function setServerEnvironmentProductionValue($serverEnvironmentProductionValue) {
		$this->serverEnvironmentProductionValue = $serverEnvironmentProductionValue;
		return $this;
	}

	/**
	 * 获取当前运行环境下，配置文件目录路径
	 * @return type
	 */
	public function getConfigCurrentDirName() {
		$name = $this->getConfigDevelopmentDirName();
		switch ($this->environment) {
			case Sr::ENV_DEVELOPMENT :
				$name = $this->getConfigDevelopmentDirName();
				break;
			case Sr::ENV_TESTING :
				$name = $this->getConfigTestingDirName();
				break;
			case Sr::ENV_PRODUCTION :
				$name = $this->getConfigProductionDirName();
				break;
		}
		return $name;
	}

	public function getEnvironment() {
		return $this->environment;
	}

	public function setEnvironment($environment) {
		if (!in_array($environment, array(Sr::ENV_DEVELOPMENT, Sr::ENV_PRODUCTION, Sr::ENV_TESTING))) {
			throw new Soter_Exception_500('wrong parameter value[' . $environment . '] of setEnvironment(), should be one of [Sr::ENV_DEVELOPMENT,Sr::ENV_PRODUCTION,Sr::ENV_TESTING]');
		}
		$this->environment = $environment;
		return $this;
	}

	public function getConfigDirName() {
		return $this->configDirName;
	}

	public function getConfigTestingDirName() {
		return $this->configTestingDirName;
	}

	public function getConfigProductionDirName() {
		return $this->configProductionDirName;
	}

	public function getConfigDevelopmentDirName() {
		return $this->configDevelopmentDirName;
	}

	public function setConfigDirName($configDirName) {
		$this->configDirName = $configDirName;
		return $this;
	}

	public function setConfigTestingDirName($configTestingDirName) {
		$this->configTestingDirName = $configTestingDirName;
		return $this;
	}

	public function setConfigProductionDirName($configProductionDirName) {
		$this->configProductionDirName = $configProductionDirName;
		return $this;
	}

	public function setConfigDevelopmentDirName($configDevelopmentDirName) {
		$this->configDevelopmentDirName = $configDevelopmentDirName;
		return $this;
	}

	/**
	 * 
	 * @return Soter_Route
	 */
	public function getRoute() {
		return empty($this->route) ? new Soter_Route() : $this->route;
	}

	public function setRoute(&$route) {
		$this->route = $route;
		return $this;
	}

	public function getLibraryDirName() {
		return $this->libraryDirName;
	}

	public function setLibraryDirName($libraryDirName) {
		$this->libraryDirName = $libraryDirName;
		return $this;
	}

	public function getHmvcDirName() {
		return $this->hmvcDirName;
	}

	public function setHmvcDirName($hmvcDirName) {
		$this->hmvcDirName = $hmvcDirName;
		return $this;
	}

	public function getHmvcModules() {
		return $this->hmvcModules;
	}

	public function setHmvcModules($hmvcModules) {
		$this->hmvcModules = $hmvcModules;
		return $this;
	}

	public function getControllerDirName() {
		return $this->controllerDirName;
	}

	public function setControllerDirName($controllerDirName) {
		$this->controllerDirName = $controllerDirName;
		return $this;
	}

	public function getExceptionHandle() {
		return $this->exceptionHandle;
	}

	public function setExceptionHandle($exceptionHandle) {
		$this->exceptionHandle = $exceptionHandle;
		return $this;
	}

	public function getApplicationDir() {
		return $this->applicationDir;
	}

	public function getIndexDir() {
		return $this->indexDir;
	}

	public function getIndexName() {
		return $this->indexName;
	}

	public function getLoggerWriterContainer() {
		return $this->loggerWriterContainer;
	}

	public function setApplicationDir($applicationDir) {
		$this->applicationDir = Sr::realPath($applicationDir) . '/';
		if (empty($this->primaryApplicationDir)) {
			$this->primaryApplicationDir = $this->applicationDir;
		}
		return $this;
	}

	public function setIndexDir($indexDir) {
		$this->indexDir = Sr::realPath($indexDir) . '/';
		return $this;
	}

	public function setIndexName($indexName) {
		$this->indexName = $indexName;
		return $this;
	}

	public function setLoggerWriterContainer(Soter_Logger_Writer $loggerWriterContainer) {
		$this->loggerWriterContainer = $loggerWriterContainer;
		return $this;
	}

	public function getMethodPrefix() {
		return $this->methodPrefix;
	}

	public function getMethodUriSubfix() {
		return $this->methodUriSubfix;
	}

	public function setMethodPrefix($methodPrefix) {
		$this->methodPrefix = $methodPrefix;
		return $this;
	}

	public function setMethodUriSubfix($methodUriSubfix) {
		if (!$methodUriSubfix) {
			throw new Soter_Exception_500('"Method Uri Subfix" can not be empty.');
		}
		$this->methodUriSubfix = $methodUriSubfix;
		return $this;
	}

	public function getDefaultController() {
		return $this->defaultController;
	}

	public function getDefaultMethod() {
		return $this->defaultMethod;
	}

	public function setDefaultController($defaultController) {
		$this->defaultController = $defaultController;
		return $this;
	}

	public function setDefaultMethod($defaultMethod) {
		$this->defaultMethod = $defaultMethod;
		return $this;
	}

	public function getClassesDirName() {
		return $this->classesDirName;
	}

	public function setClassesDirName($classesDirName) {
		$this->classesDirName = $classesDirName;
		return $this;
	}

	public function getPackages() {
		return array_merge($this->packageMasterContainer, $this->packageContainer);
	}

	public function addMasterPackages(Array $packagesPath) {
		foreach ($packagesPath as $packagePath) {
			$this->addMasterPackage($packagePath);
		}
		return $this;
	}

	public function addMasterPackage($packagePath) {
		$packagePath = Sr::realPath($packagePath) . '/';
		if (!in_array($packagePath, $this->packageMasterContainer)) {
			//注册“包”到主包容器中
			array_push($this->packageMasterContainer, $packagePath);
			if (file_exists($library = $packagePath . $this->getLibraryDirName() . '/')) {
				array_push($this->packageMasterContainer, $library);
			}
		}
		return $this;
	}

	public function addPackages(Array $packagesPath) {
		foreach ($packagesPath as $packagePath) {
			$this->addPackage($packagePath);
		}
		return $this;
	}

	public function addPackage($packagePath) {
		$packagePath = Sr::realPath($packagePath) . '/';
		if (!in_array($packagePath, $this->packageContainer)) {
			//注册“包”到包容器中
			array_push($this->packageContainer, $packagePath);
			if (file_exists($library = $packagePath . $this->getLibraryDirName() . '/')) {
				array_push($this->packageContainer, $library);
			}
		}
		return $this;
	}

	/**
	 * 加载项目目录下的bootstrap.php配置
	 */
	public function bootstrap() {
		//引入“bootstrap”配置
		if (file_exists($bootstrap = $this->getApplicationDir() . 'bootstrap.php')) {
			Sr::includeOnce($bootstrap);
		}
	}

	public function getShowError() {
		return $this->showError;
	}

	public function getRoutersContainer() {
		return $this->routersContainer;
	}

	public function setShowError($showError) {
		$this->showError = $showError;
		return $this;
	}

	/**
	 * 
	 * @return Soter_Request
	 */
	public function getRequest() {
		return $this->request;
	}

	public function setRequest(Soter_Request $request) {
		$this->request = $request;
		return $this;
	}

	public function getExcptionErrorJsonMessageName() {
		return $this->excptionErrorJsonMessageName;
	}

	public function getExcptionErrorJsonFileName() {
		return $this->excptionErrorJsonFileName;
	}

	public function getExcptionErrorJsonLineName() {
		return $this->excptionErrorJsonLineName;
	}

	public function getExcptionErrorJsonTypeName() {
		return $this->excptionErrorJsonTypeName;
	}

	public function getExcptionErrorJsonCodeName() {
		return $this->excptionErrorJsonCodeName;
	}

	public function getExcptionErrorJsonTraceName() {
		return $this->excptionErrorJsonTraceName;
	}

	public function getExcptionErrorJsonTimeName() {
		return $this->excptionErrorJsonTimeName;
	}

	public function setExcptionErrorJsonTimeName($excptionErrorJsonTimeName) {
		$this->excptionErrorJsonTimeName = $excptionErrorJsonTimeName;
		return $this;
	}

	public function setExcptionErrorJsonMessageName($excptionErrorJsonMessageName) {
		$this->excptionErrorJsonMessageName = $excptionErrorJsonMessageName;
		return $this;
	}

	public function setExcptionErrorJsonFileName($excptionErrorJsonFileName) {
		$this->excptionErrorJsonFileName = $excptionErrorJsonFileName;
		return $this;
	}

	public function setExcptionErrorJsonLineName($excptionErrorJsonLineName) {
		$this->excptionErrorJsonLineName = $excptionErrorJsonLineName;
		return $this;
	}

	public function setExcptionErrorJsonTypeName($excptionErrorJsonTypeName) {
		$this->excptionErrorJsonTypeName = $excptionErrorJsonTypeName;
		return $this;
	}

	public function setExcptionErrorJsonCodeName($excptionErrorJsonCodeName) {
		$this->excptionErrorJsonCodeName = $excptionErrorJsonCodeName;
		return $this;
	}

	public function addRouter(Soter_Router $router) {
		array_unshift($this->routersContainer, $router);
		return $this;
	}

	public function getRouters() {
		return $this->routersContainer;
	}

	public function addLoggerWriter(Soter_Logger_Writer $loggerWriter) {
		$this->loggerWriterContainer[] = $loggerWriter;
		return $this;
	}

	public function getLoggerWriters() {
		return $this->loggerWriterContainer;
	}

	public function getIsRewrite() {
		return $this->isRewrite;
	}

	public function setTimeZone($timeZone) {
		date_default_timezone_set($timeZone);
		return $this;
	}

	public function setIsRewrite($isRewrite) {
		$this->isRewrite = $isRewrite;
		return $this;
	}

}

class Soter_Logger_Writer_Dispatcher {

	private static $instance;

	public static function initialize() {
		if (empty(self::$instance)) {
			self::$instance = new self();
			error_reporting(E_ALL);
			//只在web和命令行模式下关闭错误显示，插件模式不应该关闭
			if (!Sr::isPluginMode()) {
				ini_set('display_errors', FALSE);
			}
			set_exception_handler(array(self::$instance, 'handleException'));
			set_error_handler(array(self::$instance, 'handleError'));
			register_shutdown_function(array(self::$instance, 'handleFatal'));
		}
	}

	final public function handleException(Exception $exception) {

		if (is_subclass_of($exception, 'Soter_Exception')) {
			$this->dispatch($exception);
		} else {
			$this->dispatch(new Soter_Exception_500($exception->getMessage(), $exception->getCode(), get_class($exception), $exception->getFile(), $exception->getLine()));
		}
	}

	final public function handleError($code, $message, $file, $line) {
		if (0 == error_reporting()) {
			return;
		}
		$this->dispatch(new Soter_Exception_500($message, $code, 'General Error', $file, $line));
	}

	final public function handleFatal() {
		if (0 == error_reporting()) {
			return;
		}
		$lastError = error_get_last();
		$fatalError = array(1, 256, 64, 16, 4, 4096);
		if (!isset($lastError["type"]) || !in_array($lastError["type"], $fatalError)) {
			return;
		}
		$this->dispatch(new Soter_Exception_500($lastError['message'], $lastError['type'], 'Fatal Error', $lastError['file'], $lastError['line']));
	}

	final public function dispatch(Soter_Exception $exception) {
		$config = Sr::config();
		ini_set('display_errors', TRUE);
		$loggerWriters = $config->getLoggerWriters();
		foreach ($loggerWriters as $loggerWriter) {
			$loggerWriter->write($exception);
		}
		if ($config->getShowError()) {
			$handle = $config->getExceptionHandle();
			if ($handle instanceof Soter_Exception_Handle) {
				$handle->handle($exception);
			} else {
				$exception->render();
			}
		}
		exit();
	}

}

class Soter_Logger_FileWriter implements Soter_Logger_Writer {

	private $logsDirPath;

	public function __construct($logsDirPath) {
		$this->logsDirPath = Sr::realPath($logsDirPath) . '/' . date(Sr::config()->getLogsSubDirNameFormat()) . '/';
	}

	public function write(Soter_Exception $exception) {
		$content = 'Domain : ' . Sr::server('http_host') . "\n"
			. 'ClientIP : ' . Sr::server('SERVER_ADDR') . "\n"
			. 'ServerIP : ' . Sr::serverIp() . "\n"
			. 'ServerHostname : ' . Sr::hostname() . "\n"
			. (!Sr::isCli() ? 'Request Uri : ' . Sr::server('request_uri') : '') . "\n"
			. (!Sr::isCli() ? 'Get Data : ' . json_encode(Sr::get()) : '') . "\n"
			. (!Sr::isCli() ? 'Post Data : ' . json_encode(Sr::post()) : '') . "\n"
			. (!Sr::isCli() ? 'Cookie Data : ' . json_encode(Sr::cookie()) : '') . "\n"
			. (!Sr::isCli() ? 'Server Data : ' . json_encode(Sr::server()) : '') . "\n"
			. $exception->renderCli() . "\n";
		if (!is_dir($this->logsDirPath)) {
			mkdir($this->logsDirPath, 0700, true);
		}
		if (!file_exists($logsFilePath = $this->logsDirPath . 'logs.php')) {
			$content = '<?php defined("IN_SOTER") or exit();?>' . "\n" . $content;
		}
		file_put_contents($logsFilePath, $content, LOCK_EX | FILE_APPEND);
	}

}

class Soter_Maintain_Handle_Default implements Soter_Maintain_Handle {

	public function handle() {
		header('Content-type: text/html;charset=utf-8');
		echo '<center><h2>server is under maintenance</h2><h3>服务器维护中</h3>' . date('Y/m/d H:i:s e') . '</center>';
	}

}

class Soter_Uri_Rewriter_Default implements Soter_Uri_Rewriter {

	public function rewrite($uri) {
		return $uri;
	}

}

class Soter_Exception_Handle_Default implements Soter_Exception_Handle {

	public function handle(Soter_Exception $exception) {
		$exception->render();
	}

}

class Soter_Database_SlowQuery_Handle_Default implements Soter_Database_SlowQuery_Handle {

	public function handle($sql, $explainString, $time) {
		$dir = Sr::config()->getPrimaryApplicationDir() . 'storage/slow-query-debug/';
		$file = $dir . 'slow-query-debug.php';
		if (!is_dir($dir)) {
			mkdir($dir, 0700, true);
		}
		$content = "\nSQL : " . $sql
			. "\nExplain : " . $explainString
			. "\nUsingTime : " . $time . " ms"
			. "\nTime : " . date('Y-m-d H:i:s') . "\n";
		if (!file_exists($file)) {
			$content = '<?php defined("IN_SOTER") or exit();?>' . "\n" . $content;
		}
		file_put_contents($file, $content, LOCK_EX | FILE_APPEND);
	}

}

class Soter_Database_Index_Handle_Default implements Soter_Database_Index_Handle {

	public function handle($sql, $explainString, $time) {
		$dir = Sr::config()->getPrimaryApplicationDir() . 'storage/index-debug/';
		$file = $dir . 'index-debug.php';
		if (!is_dir($dir)) {
			mkdir($dir, 0700, true);
		}
		$content = "\nSQL : " . $sql
			. "\nExplain : " . $explainString
			. "\nUsingTime : " . $time . " ms"
			. "\nTime : " . date('Y-m-d H:i:s') . "\n";
		if (!file_exists($file)) {
			$content = '<?php defined("IN_SOTER") or exit();?>' . "\n" . $content;
		}
		file_put_contents($file, $content, LOCK_EX | FILE_APPEND);
	}

}

class Soter_Cache_File implements Soter_Cache {

	private $_cacheDirPath;

	public function __construct($cacheFileName = '') {
		$cacheDirPath = empty($cacheFileName) ? Sr::config()->getPrimaryApplicationDir() . 'storage/cache/' : Sr::config($cacheFileName);
		$this->_cacheDirPath = Sr::realPath($cacheDirPath) . '/';
		if (!is_dir($this->_cacheDirPath)) {
			mkdir($this->_cacheDirPath, 0700, true);
		}
		if (!is_writable($this->_cacheDirPath)) {
			throw new Soter_Exception_500('cache dir [ ' . Sr::safePath($this->_cacheDirPath) . ' ] not writable');
		}
	}

	private function _hashKey($key) {
		return md5($key);
	}

	private function _hashKeyPath($key) {
		$key = md5($key);
		$len = strlen($key);
		return $this->_cacheDirPath . $key{$len - 1} . '/' . $key{$len - 2} . '/' . $key{$len - 3} . '/';
	}

	private function pack($userData, $cacheTime) {
		$cacheTime = (int) $cacheTime;
		return @serialize(array(
			    'userData' => $userData,
			    'expireTime' => ($cacheTime == 0 ? 0 : time() + $cacheTime)
		));
	}

	private function unpack($cacheData) {
		$cacheData = @unserialize($cacheData);
		if (is_array($cacheData) && isset($cacheData['userData']) && isset($cacheData['expireTime'])) {
			if ($cacheData['expireTime'] == 0) {
				return $cacheData['userData'];
			}
			return $cacheData['expireTime'] > time() ? $cacheData['userData'] : NULL;
		} else {
			return NULL;
		}
	}

	public function clean() {
		return Sr::rmdir($this->_cacheDirPath, false);
	}

	public function delete($key) {
		if (empty($key)) {
			return false;
		}
		$key = $this->_hashKey($key);
		$filePath = $this->_hashKeyPath($key) . $key;
		if (file_exists($filePath)) {
			return @unlink($filePath);
		}
		return true;
	}

	/**
	 * 成功返回数据，失败返回null
	 * @param type $key
	 * @return type
	 */
	public function get($key) {
		if (empty($key)) {
			return null;
		}
		$key = $this->_hashKey($key);
		$filePath = $this->_hashKeyPath($key) . $key;
		if (file_exists($filePath)) {
			$cacheData = file_get_contents($filePath);
			$userData = $this->unpack($cacheData);
			return is_null($userData) ? null : $userData;
		}
		return NULL;
	}

	/**
	 * 成功返回true，失败返回false
	 * @param type $key       缓存key
	 * @param type $value     缓存数据
	 * @param type $cacheTime 缓存时间，单位秒
	 * @return boolean
	 */
	public function set($key, $value, $cacheTime) {
		if (empty($key)) {
			return false;
		}
		$key = $this->_hashKey($key);
		$cacheDir = $this->_hashKeyPath($key);
		$filePath = $cacheDir . $key;
		if (!is_dir($cacheDir)) {
			mkdir($cacheDir, 0700, true);
		}
		$cacheData = $this->pack($value, $cacheTime);
		if (empty($cacheData)) {
			return false;
		}
		return file_put_contents($filePath, $cacheData, LOCK_EX);
	}

}

class Soter_Cache_Memcached implements Soter_Cache {

	private $config, $handle;

	public function __construct($configFileName) {
		if (is_array($configFileName)) {
			$this->config = $configFileName;
		} else {
			$this->config = Sr::config($configFileName);
		}
	}

	private function _init() {
		if (empty($this->handle)) {
			$this->handle = new Memcached();
			foreach ($this->config as $server) {
				if ($server[2] > 0) {
					$this->handle->addServer($server[0], $server[1], $server[2]);
				} else {
					$this->handle->addServer($server[0], $server[1]);
				}
			}
		}
	}

	public function clean() {
		$this->_init();
		$this->handle->flush();
	}

	public function delete($key) {
		$this->_init();
		$this->handle->delete($key);
	}

	public function get($key) {
		$this->_init();
		return ($data = $this->handle->get($key)) ? $data : null;
	}

	public function set($key, $value, $cacheTime) {
		$this->_init();
		return $this->handle->set($key, $value, $cacheTime > 0 ? (time() + $cacheTime) : 0);
	}

}

class Soter_Cache_Memcache implements Soter_Cache {

	private $config, $handle;

	public function __construct($configFileName) {
		if (is_array($configFileName)) {
			$this->config = $configFileName;
		} else {
			$this->config = Sr::config($configFileName);
		}
	}

	private function _init() {
		if (empty($this->handle)) {
			$this->handle = new Memcache();
			foreach ($this->config as $server) {
				$this->handle->addserver($server[0], $server[1]);
			}
		}
	}

	public function clean() {
		$this->_init();
		return $this->handle->flush();
	}

	public function delete($key) {
		$this->_init();
		return $this->handle->delete($key);
	}

	public function get($key) {
		$this->_init();
		return ($data = $this->handle->get($key)) ? $data : null;
	}

	public function set($key, $value, $cacheTime) {
		$this->_init();
		return $this->handle->set($key, $value, false, $cacheTime);
	}

}

class Soter_Cache_Apc implements Soter_Cache {

	public function clean() {
		@apc_clear_cache();
		@apc_clear_cache("user");
	}

	public function delete($key) {
		return apc_delete($key);
	}

	public function get($key) {
		$data = apc_fetch($key, $bo);
		if ($bo === false) {
			return null;
		}
		return $data;
	}

	public function set($key, $value, $cacheTime) {
		return apc_store($key, $value, $cacheTime);
	}

}

class Soter_Cache_Redis implements Soter_Cache {

	private $config, $handle;

	private function _init() {
		if (empty($this->handle)) {
			$this->handle = array();
			foreach (array('masters', 'slaves') as $type) {
				foreach ($this->config[$type] as $k => $config) {
					$this->handle[$type][$k] = new Redis();
					if ($config['type'] == 'sock') {
						$this->handle[$type][$k]->connect($config['sock']);
					} else {
						$this->handle[$type][$k]->connect($config['host'], $config['port'], $config['timeout'], $config['retry']);
					}
					if (!is_null($config['password'])) {
						$this->handle[$type][$k]->auth($config['password']);
					}
					if (!is_null($config['prefix'])) {
						if ($config['prefix']{strlen($config['prefix']) - 1} != ':') {
							$config['prefix'].=':';
						}
						$this->handle[$type][$k]->setOption(Redis::OPT_PREFIX, $config['prefix']);
					}
				}
			}
			if (empty($this->handle['slaves']) && !empty($this->handle['masters'])) {
				$this->handle['slaves'] = array();
				$this->handle['slaves'][0] = &$this->handle['masters'][key($this->handle['masters'])];
			}
		}
	}

	public function __construct($configFileName) {
		if (is_array($configFileName)) {
			$this->config = $configFileName;
		} else {
			$this->config = Sr::config($configFileName);
		}
	}

	public function clean() {
		$this->_init();
		$status = true;
		foreach ($this->handle['masters'] as &$handle) {
			$status = $status & $handle->flushDB();
		}
		return $status;
	}

	public function delete($key) {
		$this->_init();
		$status = true;
		foreach ($this->handle['masters'] as &$handle) {
			$status = $status & $handle->delete($key);
		}
		return $status;
	}

	public function get($key) {
		$this->_init();
		$k = array_rand($this->handle['slaves']);
		$handle = &$this->handle['slaves'][$k];
		if ($data = $handle->get($key)) {
			return @unserialize($data);
		} else {
			return null;
		}
	}

	public function set($key, $value, $cacheTime) {
		$this->_init();
		$value = serialize($value);
		foreach ($this->handle['masters'] as &$handle) {
			if ($cacheTime) {
				return $handle->setex($key, $cacheTime, $value);
			} else {
				return $handle->set($key, $value);
			}
		}
	}

}

class Soter_Generator extends Soter_Task {

	public function execute(Soter_CliArgs $args) {
		$config = Sr::config();
		$name = $args->get('name');
		$type = $args->get('type');
		$force = $args->get('overwrite');
		if (empty($name)) {
			exit('name required , please use : --name=<Name>');
		}
		if (empty($type)) {
			exit('type required , please use : --type=<Type>');
		}
		$classesDir = $config->getPrimaryApplicationDir() . $config->getClassesDirName() . '/';
		$info = array(
		    'controller' => array(
			'dir' => $config->getControllerDirName(),
			'parentClass' => 'Soter_Controller',
			'methodName' => Sr::config()->getMethodPrefix() . 'index()',
			'nameTip' => 'Controller'
		    ),
		    'business' => array(
			'dir' => $config->getBusinessDirName(),
			'parentClass' => 'Soter_Business',
			'methodName' => 'business()',
			'nameTip' => 'Business'
		    ),
		    'model' => array(
			'dir' => $config->getModelDirName(),
			'parentClass' => 'Soter_Model',
			'methodName' => 'model()',
			'nameTip' => 'Model'
		    ),
		    'task' => array(
			'dir' => $config->getTaskDirName(),
			'parentClass' => 'Soter_Task',
			'methodName' => 'execute(Soter_CliArgs $args)',
			'nameTip' => 'Task'
		    )
		);
		if (!isset($info[$type])) {
			exit('[ Error ]' . "\n" . 'Type : [ ' . $type . ' ]');
		}
		$classname = $info[$type]['dir'] . '_' . $name;
		$file = $classesDir . str_replace('_', '/', $classname) . '.php';
		$method = $info[$type]['methodName'];
		$parentClass = $info[$type]['parentClass'];
		$tip = $info[$type]['nameTip'];
		if (file_exists($file)) {
			if ($force) {
				$this->writeFile($classname, $method, $parentClass, $file, $tip);
			} else {
				exit('[ Error ]' . "\n" . $tip . ' [ ' . $classname . ' ] already exists , ' . "{$file}\n" . 'you can use --overwrite to overwrite the file.');
			}
		} else {
			$this->writeFile($classname, $method, $parentClass, $file, $tip);
		}
	}

	private function writeFile($classname, $method, $parentClass, $file, $tip) {
		$dir = dirname($file);
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		$code = "<?php\nclass  {$classname} extends {$parentClass} {\n	public function {$method} {\n		\n	}\n}";
		if (file_put_contents($file, $code)) {
			echo "[ Successfull ]\n{$tip} [ $classname ] created successfully \n" . $file;
		}
	}

}

class Soter_Generator_Mysql extends Soter_Task {

	public function execute(Soter_CliArgs $args) {
		$config = Sr::config();
		$name = $args->get('name');
		$type = $args->get('type');
		$force = $args->get('overwrite');
		$table = $args->get('table');
		$dbGroup = $args->get('db');
		if (empty($name)) {
			exit('name required , please use : --name=<Name>');
		}
		if (empty($table)) {
			exit('table name required , please use : --table=<Table Name>');
		}
		if (empty($type)) {
			exit('type required , please use : --type=<Type>');
		}
		$columns = self::getTableFieldsInfo($table, $dbGroup);
		$primaryKey = '';

		$classesDir = $config->getPrimaryApplicationDir() . $config->getClassesDirName() . '/';
		$info = array(
		    'bean' => array(
			'dir' => $config->getBeanDirName(),
			'parentClass' => 'Soter_Bean',
			'nameTip' => 'Bean'
		    ),
		    'dao' => array(
			'dir' => $config->getDaoDirName(),
			'parentClass' => 'Soter_Dao',
			'nameTip' => 'Dao'
		    ),
		);
		if (!isset($info[$type])) {
			exit('[ Error ]' . "\n" . 'Type : [ ' . $type . ' ]');
		}
		$classname = $info[$type]['dir'] . '_' . $name;
		$file = $classesDir . str_replace('_', '/', $classname) . '.php';
		$parentClass = $info[$type]['parentClass'];
		$tip = $info[$type]['nameTip'];
		$dir = dirname($file);
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		if ($type == 'bean') {
			$methods = array();
			$fields = array();
			$fieldTemplate = "	//{comment}\n	private \${column0};";
			$methodTemplate = "	public function get{column}() {\n		return \$this->{column0};\n	}\n\n	public function set{column}(\${column0}) {\n		\$this->{column0} = \${column0};\n		return \$this;\n	}";
			foreach ($columns as $value) {
				$column = ucfirst($value['name']);
				$column0 = $value['name'];
				$fields[] = str_replace(array('{column0}', '{comment}'), array($column0, $value['comment']), $fieldTemplate);
				$methods[] = str_replace(array('{column}', '{column0}'), array($column, $column0), $methodTemplate);
			}
			$code = "<?php\n\nclass {$classname} extends {$parentClass} {\n\n{fields}\n\n{methods}\n\n}";
			$code = str_replace(array('{fields}', '{methods}'), array(implode("\n\n", $fields), implode("\n\n", $methods)), $code);
		} else {
			$columnsString = '';
			$_columns = array();
			foreach ($columns as $value) {
				if ($value['primary']) {
					$primaryKey = $value['name'];
				}
				$_columns[] = '\'' . $value['name'] . "'//" . $value['comment'] . "\n				";
			}
			$columnsString = "array(\n				" . implode(',', $_columns) . ')';
			$code = "<?php\n\nclass {$classname} extends {$parentClass} {\n\n	public function getColumns() {\n		return {columns};\n	}\n\n	public function getPrimaryKey() {\n		return '{primaryKey}';\n	}\n\n	public function getTable() {\n		return '{table}';\n	}\n\n}\n";
			$code = str_replace(array('{columns}', '{primaryKey}', '{table}'), array($columnsString, $primaryKey, $table), $code);
		}
		if (file_exists($file)) {
			if ($force) {
				if (file_put_contents($file, $code)) {
					echo "[ Successfull ]\n{$tip} [ $classname ] created successfully \n" . $file;
				}
			} else {
				exit('[ Error ]' . "\n" . $tip . ' [ ' . $classname . ' ] already exists , ' . "{$file}\n" . 'you can use --overwrite to overwrite the file.');
			}
		} else {
			if (file_put_contents($file, $code)) {
				echo "[ Successfull ]\n{$tip} [ $classname ] created successfully \n" . $file;
			}
		}
	}

	/**
	 * 获取表字段信息，并返回
	 * 提示：
	 * 只适用于mysql数据库
	 * @param type $tableName   不含前缀的表名称
	 * @param type $db           数据库组配置名称，或者数据库对象，或者数据库配置数组
	 * @return array $info
	 */
	public static function getTableFieldsInfo($tableName, $db) {
		if (!is_object($db)) {
			$db = Sr::db($db);
		}
		if ($db->getDriverType() != 'mysql') {
			throw new Soter_Exception_500('getTableFieldsInfo() only for mysql database');
		}
		$info = array();
		$result = $db->execute('SHOW FULL COLUMNS FROM ' . $db->getTablePrefix() . $tableName)->rows();
		if ($result) {
			foreach ($result as $val) {
				$info[$val['Field']] = array(
				    'name' => $val['Field'],
				    'type' => $val['Type'],
				    'comment' => $val['Comment'] ? $val['Comment'] : $val['Field'],
				    'notnull' => $val['Null'] == 'NO' ? 1 : 0,
				    'default' => $val['Default'],
				    'primary' => (strtolower($val['Key']) == 'pri'),
				    'autoinc' => (strtolower($val['Extra']) == 'auto_increment'),
				);
			}
		}
		return $info;
	}

}

class Soter_Session_Redis extends Soter_Session {

	public function init() {
		ini_set('session.save_handler', 'redis');
		ini_set('session.save_path', $this->config['path']);
	}

}

class Soter_Session_Memcached extends Soter_Session {

	public function init() {
		ini_set('session.save_handler', 'memcached');
		ini_set('session.save_path', $this->config['path']);
	}

}

class Soter_Session_Memcache extends Soter_Session {

	public function init() {
		ini_set('session.save_handler', 'memcache');
		ini_set('session.save_path', $this->config['path']);
	}

}

class Soter_Session_Mongodb extends Soter_Session {

	private $__mongo_collection = NULL;
	private $__current_session = NULL;
	private $__mongo_conn = NULL;

	public function __construct($configFileName) {
		parent::__construct($configFileName);
		$cfg = Sr::config()->getSessionConfig();
		$this->config['lifetime'] = $cfg['lifetime'];
	}

	public function connect() {
		if (is_object($this->__mongo_collection)) {
			return;
		}
		$connection_string = sprintf('mongodb://%s:%s', $this->config['host'], $this->config['port']);
		if ($this->config['user'] != null && $this->config['password'] != null) {
			$connection_string = sprintf('mongodb://%s:%s@%s:%s/%s', $this->config['user'], $this->config['password'], $this->config['host'], $this->config['port'], $this->config['database']);
		}
		$opts = array('connect' => true);
		if ($this->config['persistent'] && !empty($this->config['persistentId'])) {
			$opts['persist'] = $this->config['persistentId'];
		}
		if ($this->config['replicaSet']) {
			$opts['replicaSet'] = $this->config['replicaSet'];
		}
		$class = 'MongoClient';
		if (!class_exists($class)) {
			$class = 'Mongo';
		}
		$this->__mongo_conn = $object_conn = new $class($connection_string, $opts);
		$object_mongo = $object_conn->{$this->config['database']};
		$this->__mongo_collection = $object_mongo->{$this->config['collection']};
		if ($this->__mongo_collection == NULL) {
			throw new Soter_Exception_500('can not connect to mongodb server');
		}
	}

	public function init() {
		session_set_save_handler(array(&$this, 'open'), array(&$this, 'close'), array(&$this, 'read'), array(&$this, 'write'), array(&$this, 'destroy'), array(&$this, 'gc'));
	}

	public function open($session_path, $session_name) {
		$this->connect();
		return true;
	}

	public function close() {
		$this->__mongo_conn->close();
		return true;
	}

	public function read($session_id) {
		$result = NULL;
		$ret = '';
		$expiry = time();
		$query['_id'] = $session_id;
		$query['expiry'] = array('$gte' => $expiry);
		$result = $this->__mongo_collection->findone($query);
		if ($result) {
			$this->__current_session = $result;
			$result['expiry'] = time() + $this->config['lifetime'];
			$this->__mongo_collection->update(array("_id" => $session_id), $result);
			$ret = $result['data'];
		}
		return $ret;
	}

	public function write($session_id, $data) {
		$result = true;
		$expiry = time() + $this->config['lifetime'];
		$session_data = array();
		if (empty($this->__current_session)) {
			$session_id = $session_id;
			$session_data['_id'] = $session_id;
			$session_data['data'] = $data;
			$session_data['expiry'] = $expiry;
		} else {
			$session_data = (array) $this->__current_session;
			$session_data['data'] = $data;
			$session_data['expiry'] = $expiry;
		}
		$query['_id'] = $session_id;
		$record = $this->__mongo_collection->findOne($query);
		if ($record == null) {
			$this->__mongo_collection->insert($session_data);
		} else {
			$record['data'] = $data;
			$record['expiry'] = $expiry;
			$this->__mongo_collection->save($record);
		}
		return true;
	}

	public function destroy($session_id) {
		unset($_SESSION);
		$query['_id'] = $session_id;
		$this->__mongo_collection->remove($query);
		return true;
	}

	public function gc($max = 0) {
		$query = array();
		$query['expiry'] = array(':lt' => time());
		$this->__mongo_collection->remove($query, array('justOne' => false));
		return true;
	}

}

/**
 * @property Soter_Database_ActiveRecord $dbConnection Description
 */
class Soter_Session_Mysql extends Soter_Session {

	protected $dbConnection;
	protected $dbTable;

	public function __construct($configFileName) {
		parent::__construct($configFileName);
		$cfg = Sr::config()->getSessionConfig();
		$this->config['lifetime'] = $cfg['lifetime'];
	}

	public function init() {
		session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
	}

	public function connect() {
		$this->dbTable = $this->config['table'];
		if ($this->config['group']) {
			$this->dbConnection = Sr::db($this->config['group']);
		} else {
			$dbConfig = Soter_Database::getDefaultConfig();
			$dbConfig['database'] = $this->config['database'];
			$dbConfig['tablePrefix'] = $this->config['table_prefix'];
			$dbConfig['masters']['master01']['hostname'] = $this->config['hostname'];
			$dbConfig['masters']['master01']['port'] = $this->config['port'];
			$dbConfig['masters']['master01']['username'] = $this->config['username'];
			$dbConfig['masters']['master01']['password'] = $this->config['password'];
			$this->dbConnection = Sr::db($dbConfig);
		}
	}

	public function open($save_path, $session_name) {
		if (!is_object($this->dbConnection)) {
			$this->connect();
		}
		return TRUE;
	}

	public function close() {
		return $this->dbConnection->close();
	}

	public function read($id) {
		$result = $this->dbConnection->from($this->dbTable)->where(array('id' => $id))->execute();
		if ($result->total()) {
			$record = $result->row();
			$where['id'] = $record['id'];
			$data['timestamp'] = time() + intval($this->config['lifetime']);
			$this->dbConnection->update($this->dbTable, $data, $where)->execute();
			return $record['data'];
		} else {
			return false;
		}
		return true;
	}

	public function write($id, $sessionData) {
		$data['id'] = $id;
		$data['data'] = $sessionData;
		$data['timestamp'] = time() + intval($this->config['lifetime']);
		$this->dbConnection->replace($this->dbTable, $data);
		return $this->dbConnection->execute();
	}

	public function destroy($id) {
		unset($_SESSION);
		return $this->dbConnection->delete($this->dbTable, array('id' => $id))->execute();
	}

	public function gc($max = 0) {
		return $this->dbConnection->delete($this->dbTable, array('timestamp <' => time()))->execute();
	}

}


/**
 * @property Soter_Config $soterConfig
 */
class Soter {

	private static $soterConfig;

	/**
	 * 包类库自动加载器
	 * @param type $className
	 */
	public static function classAutoloader($className) {
		$config = self::$soterConfig;
		$className = str_replace('_', '/', $className);
		foreach (self::$soterConfig->getPackages() as $path) {
			if (file_exists($filePath = $path . $config->getClassesDirName() . '/' . $className . '.php')) {
				Sr::includeOnce($filePath);
				break;
			}
		}
	}

	/**
	 * 初始化框架配置
	 * @return \Soter_Config
	 */
	public static function initialize() {
		self::$soterConfig = new Soter_Config();
		//注册错误处理
		Soter_Logger_Writer_Dispatcher::initialize();
		//注册类自动加载
		if (function_exists('__autoload')) {
			spl_autoload_register('__autoload');
		}
		spl_autoload_register(array('Soter', 'classAutoloader'));
		//清理魔法转义
		if (get_magic_quotes_gpc()) {
			$stripList = array('_GET', '_POST', '_COOKIE');
			foreach ($stripList as $val) {
				global $$val;
				$$val = Sr::stripSlashes($$val);
			}
		}
		return self::$soterConfig;
	}

	/**
	 * 获取运行配置
	 * @return Soter_Config
	 */
	public static function &getConfig() {
		return self::$soterConfig;
	}

	/**
	 * 运行调度
	 */
	public static function run() {
		if (Sr::isPluginMode()) {
			self::runPlugin();
		} elseif (Sr::isCli()) {
			self::runCli();
		} else {
			$canRunWeb = !Sr::config()->getIsMaintainMode();
			if (!$canRunWeb) {
				foreach (Sr::config()->getMaintainIpWhitelist() as $ip) {
					$info = explode('/', $ip);
					$netmask = empty($info[1]) ? '32' : $info[1];
					if (Sr::ipInfo(Sr::clientIp() . '/' . $netmask, 'netaddress') == Sr::ipInfo($info[0] . '/' . $netmask, 'netaddress')) {
						$canRunWeb = true;
						break;
					}
				}
			}
			if ($canRunWeb) {
				self::runWeb();
			} else {
				$handle = Sr::config()->getMaintainModeHandle();
				if (is_object($handle)) {
					$handle->handle();
				}
			}
		}
	}

	/**
	 * web模式运行
	 * @throws Soter_Exception_404
	 */
	private static function runWeb() {
		$config = self::getConfig();
		//session初始化
		$sessionConfig = $config->getSessionConfig();
		@ini_set('session.auto_start', 0);
		@ini_set('session.gc_probability', 1);
		@ini_set('session.gc_divisor', 100);
		@ini_set('session.gc_maxlifetime', $sessionConfig['lifetime']);
		@ini_set('session.referer_check', '');
		@ini_set('session.entropy_file', '/dev/urandom');
		@ini_set('session.entropy_length', 16);
		@ini_set('session.use_cookies', 1);
		@ini_set('session.use_only_cookies', 1);
		@ini_set('session.use_trans_sid', 0);
		@ini_set('session.hash_function', 1);
		@ini_set('session.hash_bits_per_character', 5);
		session_cache_limiter('nocache');
		session_set_cookie_params(
			$sessionConfig['lifetime'], $sessionConfig['cookie_path'], preg_match('/^[^\\.]+$/', Sr::server('HTTP_HOST')) ? null : $sessionConfig['cookie_domain']
		);
		session_name($sessionConfig['session_name']);
		register_shutdown_function('session_write_close');
		//session托管检测
		$sessionHandle = $config->getSessionHandle();
		if ($sessionHandle && $sessionHandle instanceof Soter_Session) {
			$sessionHandle->init();
		}
		if ($sessionConfig['autostart']) {
			Sr::sessionStart();
		}
		//session初始化完毕

		$class = '';
		$method = '';
		foreach ($config->getRouters() as $router) {
			$route = $router->find($config->getRequest());
			if ($route->found()) {
				$config->setRoute($route);
				$class = $route->getController();
				$method = $route->getMethod();
				break;
			}
		}
		if (empty($class)) {
			$class = $config->getControllerDirName() . '_' . $config->getDefaultController();
		}
		if (empty($method)) {
			$method = $config->getMethodPrefix() . $config->getDefaultMethod();
		}
		$controllerObject = new $class();
		if (!($controllerObject instanceof Soter_Controller)) {
			throw new Soter_Exception_500('[ ' . $class . ' ] not a valid Soter_Controller');
		}
		if (!method_exists($controllerObject, $method)) {
			throw new Soter_Exception_404('Method [ ' . $class . '->' . $method . '() ] not found');
		}
		//方法缓存检测
		$cacheClassName = str_replace($config->getControllerDirName() . '_', '', $class);
		$cacheMethodName = str_replace($config->getMethodPrefix(), '', $method);
		$methoKey = $cacheClassName . '::' . $cacheMethodName;
		$cacheMethodConfig = $config->getMethodCacheConfig();
		if (!empty($cacheMethodConfig) && isset($cacheMethodConfig[$methoKey]) && $cacheMethodConfig[$methoKey]['cache'] && ($cacheMethoKey = $cacheMethodConfig[$methoKey]['key']())) {
			if (!($contents = Sr::cache()->get($cacheMethoKey))) {
				@ob_start();
				$response = call_user_func_array(array($controllerObject, $method), $route->getArgs());
				$contents = @ob_get_contents();
				@ob_end_clean();
				if ($response instanceof Soter_Response) {
					$contents.=$response->render();
				} elseif (!empty($response)) {
					$contents.= $response;
				}
				Sr::cache()->set($cacheMethoKey, $contents, $cacheMethodConfig[$methoKey]['time']);
			}
			echo $contents;
		} else {
			$response = call_user_func_array(array($controllerObject, $method), $route->getArgs());
			if ($response instanceof Soter_Response) {
				$response->output();
			} else {
				echo $response;
			}
		}
	}

	/**
	 * 命令行模式运行
	 */
	private static function runCli() {
		$task = Sr::getOpt('task');
		$hmvcModuleName = Sr::getOpt('hmvc');
		if (empty($task)) {
			exit('require a task name,please use --task=<taskname>' . "\n");
		}
		if (!empty($hmvcModuleName)) {
			self::checkHmvc($hmvcModuleName);
		}
		if (strpos($task, 'Soter_') === 0) {
			$taskName = $task;
		} else {
			$taskName = Soter::getConfig()->getTaskDirName() . '_' . $task;
		}
		if (!class_exists($taskName)) {
			throw new Soter_Exception_404('class [ ' . $taskName . ' ] not found');
		}
		$taskObject = new $taskName();
		if (!($taskObject instanceof Soter_Task)) {
			throw new Soter_Exception_500('[ ' . $taskName . ' ] not a valid Soter_Task');
		}
		$args = Sr::getOpt();
		$args = empty($args) ? array() : $args;
		$taskObject->execute(new Soter_CliArgs($args));
	}

	/**
	 * 插件模式运行
	 */
	private static function runPlugin() {
		//插件模式
	}

	/**
	 * 检测并加载hmvc模块,成功返回模块文件夹名称，失败返回false或抛出异常
	 * @staticvar array $loadedModules  
	 * @param type $hmvcModuleName  hmvc模块在URI中的名称，即注册配置hmvc模块数组的键名称
	 * @throws Soter_Exception_404
	 */
	public static function checkHmvc($hmvcModuleName, $throwException = true) {
		//hmvc检测
		if (!empty($hmvcModuleName)) {
			$config = Soter::getConfig();
			$hmvcModules = $config->getHmvcModules();
			if (empty($hmvcModules[$hmvcModuleName])) {
				if ($throwException) {
					throw new Soter_Exception_404('Hmvc Module [ ' . $hmvcModuleName . ' ] not found, please check your config.');
				} else {
					return FALSE;
				}
			}
			//避免重复加载，提高性能
			static $loadedModules = array();
			$hmvcModuleDirName = $hmvcModules[$hmvcModuleName];
			if (!isset($loadedModules[$hmvcModuleName])) {
				$loadedModules[$hmvcModuleName] = 1;
				//找到hmvc模块,去除hmvc模块名称，得到真正的路径
				$hmvcModulePath = $config->getApplicationDir() . $config->getHmvcDirName() . '/' . $hmvcModuleDirName . '/';
				//设置hmvc子项目目录为主目录，同时注册hmvc子项目目录到主包容器，以保证高优先级
				$config->setApplicationDir($hmvcModulePath)->addMasterPackage($hmvcModulePath)->bootstrap();
			}
			return $hmvcModuleDirName;
		}
		return FALSE;
	}

}

class Sr {

	const ENV_TESTING = 1; //测试环境
	const ENV_PRODUCTION = 2; //产品环境
	const ENV_DEVELOPMENT = 3; //开发环境

	static function arrayGet($array, $key, $default = null) {
		return isset($array[$key]) ? $array[$key] : $default;
	}

	static function dump() {
		echo!self::isCli() ? '<pre style="line-height:1.5em;font-size:14px;">' : "\n";
		@ob_start();
		$args = func_get_args();
		call_user_func_array('var_dump', $args);
		$html = @ob_get_clean();
		echo!self::isCli() ? htmlentities($html) : $html;
		echo!self::isCli() ? "</pre>" : "\n";
	}

	static function includeOnce($filePath) {
		static $includeFiles = array();
		$key = self::realPath($filePath);
		if (!isset($includeFiles[$key])) {
			include $filePath;
			$includeFiles[$key] = 1;
		}
	}

	static function realPath($path, $addSlash = false) {
		//是linux系统么？
		$unipath = PATH_SEPARATOR == ':';
		//检测一下是否是相对路径，windows下面没有:,linux下面没有/开头
		//如果是相对路径就加上当前工作目录前缀
		if (strpos($path, ':') === false && strlen($path) && $path{0} != '/') {
			$path = realpath('.') . DIRECTORY_SEPARATOR . $path;
		}
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
		$parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
		$absolutes = array();
		foreach ($parts as $part) {
			if ('.' == $part)
				continue;
			if ('..' == $part) {
				array_pop($absolutes);
			} else {
				$absolutes[] = $part;
			}
		}
		//如果是linux这里会导致linux开头的/丢失
		$path = implode(DIRECTORY_SEPARATOR, $absolutes);
		//如果是linux，修复系统前缀
		$path = $unipath ? (strlen($path) && $path{0} != '/' ? '/' . $path : $path) : $path;
		//最后统一分隔符为/，windows兼容/
		$path = str_replace(array('/', '\\'), '/', $path);
		return $path . ($addSlash ? '/' : '');
	}

	static function isCli() {
		return PHP_SAPI == 'cli';
	}

	static function stripSlashes($var) {
		if (!get_magic_quotes_gpc()) {
			return $var;
		}
		if (is_array($var)) {
			foreach ($var as $key => $val) {
				if (is_array($val)) {
					$var[$key] = self::stripSlashes($val);
				} else {
					$var[$key] = stripslashes($val);
				}
			}
		} elseif (is_string($var)) {
			$var = stripslashes($var);
		}
		return $var;
	}

	static function business($businessName) {
		$name = Soter::getConfig()->getBusinessDirName() . '_' . $businessName;
		$object = self::factory($name);
		if (!($object instanceof Soter_Business)) {
			throw new Soter_Exception_500('[ ' . $name . ' ] not a valid Soter_Bussiness');
		}
		return $object;
	}

	static function dao($daoName) {
		$name = Soter::getConfig()->getDaoDirName() . '_' . $daoName;
		$object = self::factory($name);
		if (!($object instanceof Soter_Dao)) {
			throw new Soter_Exception_500('[ ' . $name . ' ] not a valid Soter_Dao');
		}
		return $object;
	}

	static function model($modelName) {
		$name = Soter::getConfig()->getModelDirName() . '_' . $modelName;
		$object = self::factory($name);
		if (!($object instanceof Soter_Model)) {
			throw new Soter_Exception_500('[ ' . $name . ' ] not a valid Soter_Model');
		}
		return $object;
	}

	static function library($className) {
		return self::factory($className);
	}

	static function functions($functionFilename) {
		static $loadedFunctionsFile = array();
		if (isset($loadedFunctionsFile[$functionFilename])) {
			return;
		} else {
			$loadedFunctionsFile[$functionFilename] = 1;
		}
		$config = Soter::getConfig();
		$found = false;
		foreach ($config->getPackages() as $packagePath) {
			$filePath = $packagePath . $config->getFunctionsDirName() . '/' . $functionFilename . '.php';
			if (file_exists($filePath)) {
				self::includeOnce($filePath);
				$found = true;
				break;
			}
		}
		if (!$found) {
			throw new Soter_Exception_404('functions file [ ' . $functionFilename . '.php ] not found');
		}
	}

	/**
	 * web模式和命令行模式下的超级工厂方法
	 * @param type $className
	 * @return \className
	 * @throws Soter_Exception_404
	 */
	static function factory($className) {
		if (Sr::strEndsWith(strtolower($className), '.php')) {
			$className = substr($className, 0, strlen($className) - 4);
		}
		$className = str_replace('/', '_', $className);
		if (Sr::isPluginMode()) {
			throw new Soter_Exception_500('Sr::factory() only in web or cli mode');
		}
		if (!class_exists($className)) {
			throw new Soter_Exception_404("class [ $className ] not found");
		}
		return new $className();
	}

	/**
	 * 插件模式下的超级工厂类
	 * @param type $className      可以是完整的控制器类名，模型类名，类库类名
	 * @param type $hmvcModuleName hmvc模块名称，是配置里面的数组的键名
	 * @return \className
	 * @throws Soter_Exception_404
	 */
	static function plugin($className, $hmvcModuleName = null) {
		if (!Sr::isPluginMode()) {
			throw new Soter_Exception_500('Sr::plugin() only in PLUGIN mode');
		}
		//hmvc检测
		Soter::checkHmvc($hmvcModuleName);
		return new $className();
	}

	/**
	 * 判断是否是插件模式运行
	 * @return type
	 */
	static function isPluginMode() {
		return (defined('SOTER_RUN_MODE_PLUGIN') && SOTER_RUN_MODE_PLUGIN);
	}

	/**
	 * 1.不传递参数返回系统配置对象（Soter_Config）。<br/>
	 * 2.传递参数加载具体的配置<br/>
	 * @staticvar array $loadedConfig
	 * @param type $configName
	 * @return Soter_Config|mixed
	 */
	static function &config($configName = null) {
		if (empty($configName)) {
			return Soter::getConfig();
		}
		$_info = explode('.', $configName);
		$configFileName = current($_info);
		static $loadedConfig = array();
		$cfg = null;
		if (isset($loadedConfig[$configFileName])) {
			$cfg = $loadedConfig[$configFileName];
		} else {
			$config = Soter::getConfig();
			$found = false;
			foreach ($config->getPackages() as $packagePath) {
				$filePath = $packagePath . $config->getConfigDirName() . '/' . $config->getConfigCurrentDirName() . '/' . $configFileName . '.php';
				$fileDefaultPath = $packagePath . $config->getConfigDirName() . '/default/' . $configFileName . '.php';
				$contents = '';
				if (file_exists($filePath)) {
					$contents = file_get_contents($filePath);
				} elseif (file_exists($fileDefaultPath)) {
					$contents = file_get_contents($fileDefaultPath);
				}
				if ($contents) {
					$cfg = eval('?>' . $contents);
					$loadedConfig[$configFileName] = $cfg;
					$found = true;
					break;
				}
			}
			if (!$found) {
				throw new Soter_Exception_404('config file [ ' . $configFileName . '.php ] not found');
			}
		}
		if ($cfg && count($_info) > 1) {
			array_shift($_info);
			$keyStrArray = '';
			foreach ($_info as $k) {
				$keyStrArray.= "['" . $k . "']";
			}
			$val = eval('return isset($cfg' . $keyStrArray . ')?$cfg' . $keyStrArray . ':null;');
			return $val;
		} else {
			return $cfg;
		}
	}

	/**
	 * 解析命令行参数 $GLOBALS['argv'] 到一个数组
	 *
	 * 参数形式支持:
	 * -e
	 * -e <value>
	 * --long-param
	 * --long-param=<value>
	 * --long-param <value>
	 * <value>
	 *
	 */
	static function getOpt($key = null) {
		if (!self::isCli()) {
			return null;
		}
		$noopt = array();
		static $result = array();
		static $parsed = false;
		if (!$parsed) {
			$parsed = true;
			$params = self::arrayGet($GLOBALS, 'argv', array());
			reset($params);
			while (list($tmp, $p) = each($params)) {
				if ($p{0} == '-') {
					$pname = substr($p, 1);
					$value = true;
					if ($pname{0} == '-') {
						$pname = substr($pname, 1);
						if (strpos($p, '=') !== false) {
							list($pname, $value) = explode('=', substr($p, 2), 2);
						}
					}
					$nextparm = current($params);
					if (!in_array($pname, $noopt) && $value === true && $nextparm !== false && $nextparm{0} != '-') {
						list($tmp, $value) = each($params);
					}
					$result[$pname] = $value;
				} else {
					$result[] = $p;
				}
			}
		}
		return empty($key) ? $result : (isset($result[$key]) ? $result[$key] : null);
	}

	static function get($key = null, $default = null, $xssClean = false) {
		$value = is_null($key) ? $_GET : self::arrayGet($_GET, $key, $default);
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function getPost($key, $default = null, $xssClean = false) {
		$getValue = self::arrayGet($_GET, $key);
		$value = is_null($getValue) ? self::arrayGet($_POST, $key, $default) : $getValue;
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function post($key = null, $default = null, $xssClean = false) {
		$value = is_null($key) ? $_POST : self::arrayGet($_POST, $key, $default);
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function postGet($key, $default = null, $xssClean = false) {
		$postValue = self::arrayGet($_POST, $key);
		$value = is_null($postValue) ? self::arrayGet($_GET, $key, $default) : $postValue;
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function session($key = null, $default = null, $xssClean = false) {
		self::sessionStart();
		$value = is_null($key) ? $_SESSION : self::arrayGet($_SESSION, $key, $default);
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function sessionSet($key = null, $value = null) {
		self::sessionStart();
		if (is_array($key)) {
			$_SESSION = array_merge($_SESSION, $key);
		} else {
			$_SESSION[$key] = $value;
		}
	}

	static function server($key = null, $default = null) {
		return is_null($key) ? $_SERVER : self::arrayGet($_SERVER, strtoupper($key), $default);
	}

	/**
	 * 获取原始的POST数据，即php://input获取到的
	 * @return type
	 */
	static function postRawBody() {
		return file_get_contents('php://input');
	}

	/**
	 * 获取一个cookie
	 * 提醒:
	 * 该方法会在key前面加上系统配置里面的getCookiePrefix()
	 * 如果想不加前缀，获取原始key的cookie，可以使用方法：Sr::cookieRaw();
	 * @return type
	 */
	static function cookie($key = null, $default = null, $xssClean = false) {
		$key = is_null($key) ? null : Sr::config()->getCookiePrefix() . $key;
		$value = self::cookieRaw($key, $default, $xssClean);
		return $xssClean ? self::xssClean($value) : $value;
	}

	static function cookieRaw($key = null, $default = null, $xssClean = false) {
		$value = is_null($key) ? $_COOKIE : self::arrayGet($_COOKIE, $key, $default);
		return $xssClean ? self::xssClean($value) : $value;
	}

	/**
	 * 设置一个cookie，该方法会在key前面加上系统配置里面的getCookiePrefix()前缀<br>
	 * 如果不想加前缀，可以使用方法：Sr::setCookieRaw()<br>
	 * 或者设置前缀为空那么Sr::cookie和Sr::cookieRaw效果一样。前缀默认就是空。
	 */
	static function setCookie($key, $value, $life = null, $path = '/', $domian = null, $http_only = false) {
		$key = Sr::config()->getCookiePrefix() . $key;
		return self::setCookieRaw($key, $value, $life, $path, $domian, $http_only);
	}

	static function setCookieRaw($key, $value, $life = null, $path = '/', $domian = null, $httpOnly = false) {
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		if (!is_null($domian)) {
			$autoDomain = $domian;
		} else {
			$host = self::server('HTTP_HOST');
			// $_host = current(explode(":", $host));
			$is_ip = preg_match('/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/', $host);
			$notRegularDomain = preg_match('/^[^\\.]+$/', $host);
			if ($is_ip) {
				$autoDomain = $host;
			} elseif ($notRegularDomain) {
				$autoDomain = NULL;
			} else {
				$autoDomain = '.' . $host;
			}
		}
		setcookie($key, $value, ($life ? $life + time() : null), $path, $autoDomain, (self::server('SERVER_PORT') == 443 ? 1 : 0), $httpOnly);
		$_COOKIE[$key] = $value;
	}

	static function xssClean($var) {
		if (is_array($var)) {
			foreach ($var as $key => $val) {
				if (is_array($val)) {
					$var[$key] = self::xss_clean($val);
				} else {
					$var[$key] = self::xssClean0($val);
				}
			}
		} elseif (is_string($var)) {
			$var = self::xssClean0($var);
		}
		return $var;
	}

	private static function xssClean0($data) {
		// Fix &entity\n;
		$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
		do {
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|iframe|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		} while ($old_data !== $data);

		// we are done...
		return $data;
	}

	/**
	 * 服务器的hostname
	 * @return type
	 */
	static function hostname() {
		return function_exists('gethostname') ? gethostname() : (function_exists('php_uname') ? php_uname('n') : 'unknown');
	}

	/**
	 * 服务器的ip
	 * @return type
	 */
	static function serverIp() {
		return self::isCli() ? gethostbyname(self::hostname()) : Sr::server('SERVER_ADDR');
	}

	static function clientIp() {
		if ($ip = self::checkClientIp(Sr::arrayGet($_SERVER, 'HTTP_X_FORWARDED_FOR'))) {
			return $ip;
		} elseif ($ip = self::server('HTTP_CLIENT_IP')) {
			return $ip;
		} elseif ($ip = Sr::arrayGet($_SERVER, 'REMOTE_ADDR')) {
			return $ip;
		} elseif ($ip = self::checkClientIp(getenv("HTTP_X_FORWARDED_FOR"))) {
			return $ip;
		} elseif ($ip = getenv("HTTP_CLIENT_IP")) {
			return $ip;
		} elseif ($ip = getenv("REMOTE_ADDR")) {
			return $ip;
		} else {
			return "Unknown";
		}
	}

	private static function checkClientIp($ip) {
		if (empty($ip)) {
			return false;
		}
		$whitelist = Sr::config()->getBackendServerIpWhitelist();
		foreach ($whitelist as $okayIp) {
			if ($okayIp == $ip) {
				return $ip;
			}
		}
		return FALSE;
	}

	static function strBeginsWith($str, $sub) {
		return ( substr($str, 0, strlen($sub)) == $sub );
	}

	static function strEndsWith($str, $sub) {
		return ( substr($str, strlen($str) - strlen($sub)) == $sub );
	}

	/**
	 * 获取IP段信息<br>
	 * $ipAddr格式：192.168.1.10/24、192.168.1.10/32<br>
	 * 传入Ip地址对Ip段地址进行处理得到相关的信息<br>
	 * 1.没有$key时，返回数组：array(<br>
	 * netmask=>网络掩码<br>
	 * count=>网络可用IP数目<br>
	 * start=>可用IP开始<br>
	 * end=>可用IP结束<br>
	 * netaddress=>网络地址<br>
	 * broadcast=>广播地址<br>
	 * )<br>
	 * 2.有$key时返回$key对应的值，$key是上面数组的键。
	 */
	static function ipInfo($ipAddr, $key = null) {
		$ipAddr = str_replace(" ", "", $ipAddr);    //去除字符串中的空格
		$arr = explode('/', $ipAddr); //对IP段进行解剖

		$ipAddr = $arr[0];    //得到IP地址
		$ipAddrArr = explode('.', $ipAddr);
		foreach ($ipAddrArr as &$v) {
			$v = intval($v); //去掉192.023.20.01其中的023的0
		}
		$ipAddr = implode('.', $ipAddrArr); //修正后的ip地址

		$netbits = intval((isset($arr[1]) ? $arr[1] : 0));   //得到掩码位

		$subnetMask = long2ip(ip2long("255.255.255.255") << (32 - $netbits));
		$ip = ip2long($ipAddr);
		$nm = ip2long($subnetMask);
		$nw = ($ip & $nm);
		$bc = $nw | (~$nm);

		$ips = array();
		$ips['netmask'] = long2ip($nm);     //网络掩码
		$ips['count'] = ($bc - $nw - 1);      //可用IP数目
		if ($ips['count'] <= 0) {
			$ips['count'] += 4294967296;
		}
		if ($netbits == 32) {
			$ips['count'] = 0;      //当$netbits是32的时候可用数目是-1，这里修正为1
			$ips['start'] = long2ip($ip);    //可用IP开始
			$ips['end'] = long2ip($ip);      //可用IP结束
		} else {
			$ips['start'] = long2ip($nw + 1);    //可用IP开始
			$ips['end'] = long2ip($bc - 1);      //可用IP结束
		}
		$bc = sprintf('%u', $bc);    //或者采用此方法转换成无符号的，修复32位操作系统中long2ip后会出现负数
		$nw = sprintf('%u', $nw);
		$ips['netaddress'] = long2ip($nw);       //网络地址
		$ips['broadcast'] = long2ip($bc);       //广播地址

		return is_null($key) ? $ips : $ips[$key];
	}

	/**
	 * 获取数据库操作对象
	 * @staticvar array $instances
	 * @param type $group  数据库配置里面的组名称，默认是default组。也可以是一个数据库组配置的数组
	 * @return \Soter_Database_ActiveRecord
	 */
	static function &db($group = '') {
		static $instances = array();
		if (is_array($group)) {
			ksort($group);
			$key = md5(var_export($group, true));
			if (!isset($instances[$key])) {
				$instances[$key] = new Soter_Database_ActiveRecord($group);
			}
			return $instances[$key];
		} else {
			if (empty($group)) {
				$config = self::config()->getDatabseConfig();
				$group = $config['default_group'];
			}
			if (!isset($instances[$group])) {
				$config = self::config()->getDatabseConfig($group);
				if (empty($config)) {
					throw new Soter_Exception_Database('unknown database config group [ ' . $group . ' ]');
				}
				$instances[$group] = new Soter_Database_ActiveRecord($config);
			}
			return $instances[$group];
		}
	}

	static function createSqlite3Database($path) {
		return new PDO('sqlite:' . $path);
	}

	/**
	 * 获取当前UNIX毫秒时间戳
	 * @return type
	 */
	static function microtime() {
		// 获取当前毫秒时间戳
		list ($s1, $s2) = explode(' ', microtime());
		$currentTime = (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
		return $currentTime;
	}

	/**
	 * 屏蔽路径中系统的绝对路径部分，转换为安全的用于显示
	 * @param type $path
	 * @return string
	 */
	static function safePath($path) {
		if (!$path) {
			return '';
		}
		$path = self::realPath($path);
		$siteRoot = self::realPath(self::server('DOCUMENT_ROOT'));
		$_path = str_replace($siteRoot, '', $path);
		$relPath = str_replace($siteRoot, '', rtrim(self::config()->getApplicationDir(), '/'));
		return '~APPPATH~' . str_replace($relPath, '', $_path);
	}

	/**
	 * 获取缓存操作对象
	 * @param type $cacheHandle
	 * @return Soter_Cache
	 */
	static function cache($cacheHandle = null) {
		if ($cacheHandle) {
			self::config()->setCacheHandle($cacheHandle);
		}
		return self::config()->getCacheHandle();
	}

	/**
	 * 删除文件夹和子文件夹
	 * @param string $dirPath   文件夹路径
	 * @param type $includeSelf 是否保留最父层文件夹
	 * @return boolean
	 */
	static function rmdir($dirPath, $includeSelf = true) {
		if (empty($dirPath)) {
			return false;
		}
		$dirPath = self::realPath($dirPath) . '/';
		foreach (scandir($dirPath) as $value) {
			if ($value == '.' || $value == '..') {
				continue;
			}
			$path = $dirPath . $value;
			if (is_dir($path)) {
				self::rmdir($path);
				@rmdir($path);
			} else {
				@unlink($path);
			}
		}
		if ($includeSelf) {
			@rmdir($dirPath);
		}
		return true;
	}

	static function view() {
		static $view;
		if (!$view) {
			$view = new Soter_View();
		}
		return $view;
	}

	/**
	 * 获取入口文件所在目录url路径。
	 * 只能在web访问时使用，在命令行下面会抛出异常。
	 * @param type $subpath  子路径或者文件路径，如果非空就会被附加在入口文件所在目录的后面
	 * @return type           
	 * @throws Exception     
	 */
	static function urlPath($subpath = null, $addSlash = true) {
		if (self::isCli()) {
			throw new Soter_Exception_500('urlPath() can not be used in cli mode');
		} else {
			$old_path = getcwd();
			$root = str_replace(array("/", "\\"), '/', self::server('DOCUMENT_ROOT'));
			chdir($root);
			$root = getcwd();
			$root = str_replace(array("/", "\\"), '/', $root);
			chdir($old_path);
			$path = str_replace(array("/", "\\"), '/', realpath('.') . ($subpath ? '/' . trim($subpath, '/\\') : ''));
			$path = self::realPath($path) . ($addSlash ? '/' : '');
			return str_replace($root, '', $path);
		}
	}

	/**
	 * 生成控制器方法的url
	 * @param type $action   控制器方法
	 * @param type $getData  get传递的参数数组，键值对，键是参数名，值是参数值
	 * @return string
	 */
	static function url($action = '', $getData = array()) {
		$index = self::config()->getIsRewrite() ? '' : self::config()->getIndexName() . '/';
		$url = self::urlPath($index . $action);
		$url = rtrim($url, '/');
		$url = $index ? $url : ($action ? $url : $url . '/');
		if (!empty($getData)) {
			$url = $url . '?';
			foreach ($getData as $k => $v) {
				$url.= $k . '=' . urlencode($v) . '&';
			}
			$url = rtrim($url, '&');
		}
		return $url;
	}

	/**
	 * $source_data和$map的key一致，$map的value是返回数据的key
	 * 根据$map的key读取$source_data中的数据，结果是以map的value为key的数数组
	 * 
	 * @param Array $map 字段映射数组,格式：array('表单name名称'=>'表字段名称',...)
	 */
	static function readData(Array $map, $sourceData = null) {
		$data = array();
		$formdata = is_null($sourceData) ? Sr::post() : $sourceData;
		foreach ($formdata as $formKey => $val) {
			if (isset($map[$formKey])) {
				$data[$map[$formKey]] = $val;
			}
		}
		return $data;
	}

	static function checkData($data, $rules, &$returnData, &$errorMessage, &$db = null) {
		static $checkRules;
		if (empty($checkRules)) {
			$defaultRules = array(
			    'array' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key]) || !is_array($value)) {
					    return false;
				    }
				    $minOkay = true;
				    if (isset($args[0])) {
					    $minOkay = count($value) >= intval($args[0]);
				    }
				    $maxOkay = true;
				    if (isset($args[1])) {
					    $minOkay = count($value) >= intval($args[1]);
				    }
				    return $minOkay && $maxOkay;
			    }, 'notArray' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    return !is_array($value);
			    }, 'default' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (is_array($value)) {
					    $i = 0;
					    foreach ($value as $k => $v) {

						    $returnValue[$k] = empty($v) ? (isset($args[$i]) ? $args[$i] : $args[0]) : $v;
						    $i++;
					    }
				    } elseif (empty($value)) {
					    $returnValue = $args[0];
				    }
				    return true;
			    }, 'optional' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    $break = !isset($data[$key]);
				    return true;
			    }, 'required' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key]) || empty($value)) {
					    return false;
				    }
				    $value = (array) $value;
				    foreach ($value as $v) {
					    if (empty($v)) {
						    return false;
					    }
				    }
				    return true;
			    }, 'requiredKey' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    $args[] = $key;
				    $args = array_unique($args);
				    foreach ($args as $k) {
					    if (!isset($data[$k])) {
						    return false;
					    }
				    }
				    return true;
			    }, 'functions' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return true;
				    }
				    $returnValue = $value;
				    if (is_array($returnValue)) {
					    foreach ($returnValue as $k => $v) {
						    foreach ($args as $function) {
							    $returnValue[$k] = $function($v);
						    }
					    }
				    } else {
					    foreach ($args as $function) {
						    $returnValue = $function($returnValue);
					    }
				    }
				    return true;
			    }, 'xss' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return true;
				    }
				    $returnValue = self::xssClean($value);
				    return true;
			    }, 'match' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key]) || !isset($args[0]) || !isset($data[$args[0]]) || $value != $data[$args[0]]) {
					    return false;
				    }
				    return true;
			    }, 'equal' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key]) || !isset($args[0]) || $value != $args[0]) {
					    return false;
				    }
				    return true;
			    }, 'enum' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $value = (array) $value;
				    foreach ($value as $v) {
					    if (!in_array($v, $args)) {
						    return false;
					    }
				    }
				    return true;
			    }, 'unique' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #比如unique[user.name] , unique[user.name,id:1]
				    if (!isset($data[$key]) || !$value || !count($args)) {
					    return false;
				    }
				    $_info = explode('.', $args[0]);
				    if (count($_info) != 2) {
					    return false;
				    }
				    $table = $_info[0];
				    $col = $_info[1];
				    if (isset($args[1])) {
					    $_id_info = explode(':', $args[1]);
					    if (count($_id_info) != 2) {
						    return false;
					    }
					    $id_col = $_id_info[0];
					    $id = $_id_info[1];
					    $id = stripos($id, '#') === 0 ? Sr::getPost(substr($id, 1)) : $id;
					    $where = array($col => $value, "$id_col <>" => $id);
				    } else {
					    $where = array($col => $value);
				    }
				    return !$db->where($where)->from($table)->execute()->total();
			    }, 'exists' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #比如exists[user.name] , exists[user.name,type:1], exists[user.name,type:1,sex:#sex]
				    if (!isset($data[$key]) || !$value || !count($args)) {
					    return false;
				    }
				    $_info = explode('.', $args[0]);
				    if (count($_info) != 2) {
					    return false;
				    }
				    $table = $_info[0];
				    $col = $_info[1];
				    $where = array($col => $value);
				    if (count($args) > 1) {
					    foreach (array_slice($args, 1) as $v) {
						    $_id_info = explode(':', $v);
						    if (count($_id_info) != 2) {
							    continue;
						    }
						    $id_col = $_id_info[0];
						    $id = $_id_info[1];
						    $id = stripos($id, '#') === 0 ? Sr::getPost(substr($id, 1)) : $id;
						    $where[$id_col] = $id;
					    }
				    }
				    return $db->where($where)->from($table)->execute()->total();
			    }, 'min_len' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = isset($args[0]) ? (mb_strlen($value, 'UTF-8') >= intval($args[0])) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'max_len' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = isset($args[0]) ? (mb_strlen($value, 'UTF-8') <= intval($args[0])) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'range_len' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = count($args) == 2 ? (mb_strlen($value, 'UTF-8') >= intval($args[0])) && (mb_strlen($value, 'UTF-8') <= intval($args[1])) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'len' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = isset($args[0]) ? (mb_strlen($value, 'UTF-8') == intval($args[0])) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'min' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = isset($args[0]) && is_numeric($value) ? $value >= $args[0] : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'max' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = isset($args[0]) && is_numeric($value) ? $value <= $args[0] : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'range' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = (count($args) == 2) && is_numeric($value) ? $value >= $args[0] && $value <= $args[1] : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'alpha' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    #纯字母
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !preg_match('/[^A-Za-z]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'alpha_num' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #纯字母和数字
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !preg_match('/[^A-Za-z0-9]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'alpha_dash' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #纯字母和数字和下划线和-
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !preg_match('/[^A-Za-z0-9_-]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'alpha_start' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #以字母开头
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^[A-Za-z]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'num' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #纯数字
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !preg_match('/[^0-9]+/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'int' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #整数
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^([-+]?[1-9]\d*|0)$/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'float' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #小数
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^([1-9]\d*|0)\.\d+$/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'numeric' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #数字-1，1.2，+3，4e5
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = is_numeric($value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'natural' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #自然数0，1，2，3，12，333
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^([1-9]\d*|0)$/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'natural_no_zero' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    #自然数不包含0
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = preg_match('/^[1-9]\d*$/', $value);
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'email' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'url' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'qq' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[1-9][0-9]{4,}$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'phone' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^(?:\d{3}-?\d{8}|\d{4}-?\d{7})$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'mobile' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1}))+\d{8})$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'zipcode' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[1-9]\d{5}(?!\d)$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'idcard' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^\d{14}(\d{4}|(\d{3}[xX])|\d{1})$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'ip' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'chs' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $count = implode(',', array_slice($args, 1, 2));
				    $count = empty($count) ? '1,' : $count;
				    $can_empty = isset($args[0]) && $args[0] == 'true';
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[\x{4e00}-\x{9fa5}]{' . $count . '}$/u', $value) : $can_empty;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'date' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'time' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^(([0-1][0-9])|([2][0-3])):([0-5][0-9])(:([0-5][0-9]))$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'datetime' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $args[0] = isset($args[0]) && $args[0] == 'true' ? TRUE : false;
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($value) ? preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30))) (([0-1][0-9])|([2][0-3])):([0-5][0-9])(:([0-5][0-9]))$/', $value) : $args[0];
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }, 'reg' => function($key, $value, $data, $args, &$returnValue, &$break, &$db) {
				    if (!isset($data[$key])) {
					    return false;
				    }
				    $v = (array) $value;
				    foreach ($v as $value) {
					    $okay = !empty($args[0]) ? preg_match($args[0], $value) : false;
					    if (!$okay) {
						    return false;
					    }
				    }
				    return true;
			    }
				);
				$userRules = Sr::config()->getDataCheckRules();
				$checkRules = (is_array($userRules) && !empty($userRules)) ? array_merge($defaultRules, $userRules) : $defaultRules;
			}
			$getCheckRuleInfo = function($_rule) {
				$matches = array();
				preg_match('|([^\[]+)(?:\[(.*)\](.?))?|', $_rule, $matches);
				$matches[1] = isset($matches[1]) ? $matches[1] : '';
				$matches[3] = !empty($matches[3]) ? $matches[3] : ',';
				$matches[2] = isset($matches[2]) ? explode($matches[3], $matches[2]) : array();
				return $matches;
			};
			$returnData = $data;
			foreach ($rules as $key => $keyRules) {
				foreach ($keyRules as $rule => $message) {
					$matches = $getCheckRuleInfo($rule);
					$_v = self::arrayGet($returnData, $key);
					$_r = $matches[1];
					$args = $matches[2];
					if (!isset($checkRules[$_r]) || !is_callable($checkRules[$_r])) {
						throw new Soter_Exception_500('error rule [ ' . $_r . ' ]');
					}
					$ruleFunction = $checkRules[$_r];
					$db = (is_object($db) && ($db instanceof Soter_Database_ActiveRecord) ) ? $db : Sr::db();
					$break = false;
					$returnValue = null;
					$isOkay = $ruleFunction($key, $_v, $data, $args, $returnValue, $break, $db);
					if (!$isOkay) {
						$errorMessage = $message;
						return false;
					}
					if (!is_null($returnValue)) {
						$returnData[$key] = $returnValue;
					}
					if ($break) {
						break;
					}
				}
			}
			return true;
		}

		static function sessionStart() {
			if (!isset($_SESSION)) {
				session_start();
			}
		}

		/**
		 * 分页函数
		 * @param type $total 一共多少记录
		 * @param type $page  当前是第几页
		 * @param type $pagesize 每页多少
		 * @param type $url    url是什么，url里面的{page}会被替换成页码
		 * @param array $order 分页条的组成，是一个数组，可以按着1-6的序号，选择分页条组成部分和每个部分的顺序
		 * @param int $a_count   分页条中a页码链接的总数量,不包含当前页的a标签，默认10个。
		 * @return type  String
		 * echo Sr::page(100,3,10,'?article/list/{page}',array(3,4,5,1,2,6));
		 */
		static function page($total, $page, $pagesize, $url, $order = array(1, 2, 3, 4, 5, 6), $a_count = 10) {
			$a_num = $a_count;
			$first = '首页';
			$last = '尾页';
			$pre = '上页';
			$next = '下页';
			$a_num = $a_num % 2 == 0 ? $a_num + 1 : $a_num;
			$pages = ceil($total / $pagesize);
			$curpage = intval($page) ? intval($page) : 1;
			$curpage = $curpage > $pages || $curpage <= 0 ? 1 : $curpage; #当前页超范围置为1
			$body = '<span class="page_body">';
			$prefix = '';
			$subfix = '';
			$start = $curpage - ($a_num - 1) / 2; #开始页
			$end = $curpage + ($a_num - 1) / 2;  #结束页
			$start = $start <= 0 ? 1 : $start;   #开始页超范围修正
			$end = $end > $pages ? $pages : $end; #结束页超范围修正
			if ($pages >= $a_num) {#总页数大于显示页数
				if ($curpage <= ($a_num - 1) / 2) {
					$end = $a_num;
				}//当前页在左半边补右边
				if ($end - $curpage <= ($a_num - 1) / 2) {
					$start-=floor($a_num / 2) - ($end - $curpage);
				}//当前页在右半边补左边
			}
			for ($i = $start; $i <= $end; $i++) {
				if ($i == $curpage) {
					$body.='<a class="page_cur_page" href="javascript:void(0);"><b>' . $i . '</b></a>';
				} else {
					$body.='<a href="' . str_replace('{page}', $i, $url) . '">' . $i . '</a>';
				}
			}
			$body.='</span>';
			$prefix = ($curpage == 1 ? '' : '<span class="page_bar_prefix"><a href="' . str_replace('{page}', 1, $url) . '">' . $first . '</a><a href="' . str_replace('{page}', $curpage - 1, $url) . '">' . $pre . '</a></span>');
			$subfix = ($curpage == $pages ? '' : '<span class="page_bar_subfix"><a href="' . str_replace('{page}', $curpage + 1, $url) . '">' . $next . '</a><a href="' . str_replace('{page}', $pages, $url) . '">' . $last . '</a></span>');
			$info = "<span class=\"page_cur\">第{$curpage}/{$pages}页</span>";
			$id = "gsd09fhas9d" . rand(100000, 1000000);
			$go = '<script>function ekup(){if(event.keyCode==13){clkyup();}}function clkyup(){var num=document.getElementById(\'' . $id . '\').value;if(!/^\d+$/.test(num)||num<=0||num>' . $pages . '){alert(\'请输入正确页码!\');return;};location=\'' . addslashes($url) . '\'.replace(/\\{page\\}/,document.getElementById(\'' . $id . '\').value);}</script><span class="page_input_num"><input onkeyup="ekup()" type="text" id="' . $id . '" style="width:40px;vertical-align:text-baseline;padding:0 2px;font-size:10px;border:1px solid gray;"/></span><span class="page_btn_go" onclick="clkyup();" style="cursor:pointer;">转到</span>';
			$total = "<span class=\"page_total\">共{$total}条</span>";
			$pagination = array(
			    $total,
			    $info,
			    $prefix,
			    $body,
			    $subfix,
			    $go
			);
			$output = array();
			if (is_null($order)) {
				$order = array(1, 2, 3, 4, 5, 6);
			}
			foreach ($order as $key) {
				if (isset($pagination[$key - 1])) {
					$output[] = $pagination[$key - 1];
				}
			}
			return $pages > 1 ? implode("", $output) : '';
		}

	}
	