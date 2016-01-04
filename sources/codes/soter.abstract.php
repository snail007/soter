<?php

abstract class Soter_Controller {
	
}

abstract class Soter_Model {
	
}

abstract class Soter_Dao {

	private $db;

	public function __construct() {
		$this->db = Sr::db();
	}

	/**
	 * 设置Dao中使用的数据库操作对象
	 * @param Soter_Database_ActiveRecord $db
	 * @return \Soter_Dao
	 */
	public function setDb(Soter_Database_ActiveRecord $db) {
		$this->db = $db;
		return $this;
	}

	/**
	 * 获取Dao中使用的数据库操作对象
	 * @return Soter_Database_ActiveRecord
	 */
	public function &getDb() {
		return $this->db;
	}

	public abstract function getTable();

	public abstract function getPrimaryKey();

	public abstract function getColumns();

	/**
	 * 添加数据
	 * @param array $data  需要添加的数据
	 * @return int 最后插入的id，失败为0
	 */
	public function insert($data) {
		$num = $this->getDb()->insert($this->getTable(), $data)->execute();
		return $num ? $this->getDb()->lastId() : 0;
	}

	/**
	 * 批量添加数据
	 * @param array $rows  需要添加的数据
	 * @return int 插入的数据中第一条的id，失败为0
	 */
	public function insertBatch($rows) {
		$num = $this->getDb()->insertBatch($this->getTable(), $rows)->execute();
		return $num ? $this->getDb()->lastId() : 0;
	}

	/**
	 * 更新数据
	 * @param type $data  需要更新的数据
	 * @param type $where     可以是where条件关联数组，还可以是主键值。
	 * @return boolean
	 */
	public function update($data, $where) {
		$where = is_array($where) ? $where : array($this->getPrimaryKey() => $where);
		return $this->getDb()->where($where)->update($this->getTable(), $data)->execute();
	}

	/**
	 * 更新数据
	 * @param type $data  需要批量更新的数据
	 * @param type $index  需要批量更新的数据中的主键名称
	 * @return boolean
	 */
	public function updateBatch($data, $index) {
		return $this->getDb()->updateBatch($this->getTable(), $data, $index)->execute();
	}

	/**
	 * 获取一条或者多条数据
	 * @param type $values      可以是一个主键的值或者主键的值数组，还可以是where条件
	 * @param boolean $isRows  返回多行记录还是单行记录，true：多行，false：单行
	 * @param type $orderBy    当返回多行记录时，可以指定排序，
	 * 			     比如：array('time'=>'desc')或者array('time'=>'desc','id'=>'asc')
	 * @return int
	 */
	public function find($values, $isRows = false, Array $orderBy = array()) {
		if (empty($values)) {
			return 0;
		}
		if (is_array($values)) {
			$is_asso = array_diff_assoc(array_keys($values), range(0, sizeof($values))) ? TRUE : FALSE;
			if ($is_asso) {
				$this->getDb()->where($values);
			} else {
				$this->getDb()->where(array($this->getPrimaryKey() => array_values($values)));
			}
		} else {
			$this->getDb()->where(array($this->getPrimaryKey() => $values));
		}
		foreach ($orderBy as $k => $v) {
			$this->getDb()->orderBy($k, $v);
		}
		if (!$isRows) {
			$this->getDb()->limit(0, 1);
		}
		$rs = $this->getDb()->from($this->getTable())->execute();
		if ($isRows) {
			return $rs->rows();
		} else {
			return $rs->row();
		}
	}

	/**
	 * 获取所有数据
	 * @param type $where   where条件数组
	 * @param type $orderBy 排序，比如：array('time'=>'desc')或者array('time'=>'desc','id'=>'asc')
	 * @param type $limit   limit数量，比如：10
	 * @param type $fields  要搜索的字段，比如：id,name。留空默认*
	 * @return type
	 */
	public function findAll($where = null, Array $orderBy = array(), $limit = null, $fields = null) {
		if (!is_null($fields)) {
			$this->getDb()->select($fields);
		}
		if (!is_null($where)) {
			$this->getDb()->where($where);
		}
		foreach ($orderBy as $k => $v) {
			$this->getDb()->orderBy($k, $v);
		}
		if (!is_null($limit)) {
			$this->getDb()->limit(0, $limit);
		}
		return $this->getDb()->from($this->getTable())->execute()->rows();
	}

	/**
	 * 根据条件获取一个字段的值或者数组
	 * @param type $col         字段名称
	 * @param type $where       可以是一个主键的值或者主键的值数组，还可以是where条件
	 * @param boolean $isRows  返回多行记录还是单行记录，true：多行，false：单行
	 * @param type $orderBy    当返回多行记录时，可以指定排序，比如：array('time'=>'desc')或者array('time'=>'desc','id'=>'asc')
	 * @return type
	 */
	public function findCol($col, $where, $isRows = false, Array $orderBy = array()) {
		$row = $this->find($where, $isRows, $orderBy);
		if (!$isRows) {
			return isset($row[$col]) ? $row[$col] : null;
		} else {
			$vals = array();
			foreach ($row as $v) {
				$vals[] = $v[$col];
			}
			return $vals;
		}
	}

	/**
	 * 
	 * 根据条件删除记录
	 * @param type $values 可以是一个主键的值或者主键主键的值数组
	 * @param type $cond   附加的where条件，关联数组
	 * 成功则返回影响的行数，失败返回false
	 */
	public function delete($values, Array $cond = NULL) {
		if (empty($values)) {
			return 0;
		}
		if (!empty($values)) {
			$this->getDb()->where(array($this->getPrimaryKey() => is_array($values) ? array_values($values) : $values));
		}
		if (!empty($cond)) {
			$this->getDb()->where($cond);
		}
		return $this->getDb()->delete($this->getTable())->execute();
	}

	/**
	 * 分页方法
	 * @param int $page       第几页
	 * @param int $pagesize   每页多少条
	 * @param string $url     基础url，里面的{page}会被替换为实际的页码
	 * @param string $fields  select的字段，全部用*，多个字段用逗号分隔
	 * @param array  $where    where条件，关联数组
	 * @param string $orderBy 排序字段，比如：array('time'=>'desc')或者array('time'=>'desc','id'=>'asc')
	 * @param array $pageBarOrder   分页条组成，可以参考手册分页条部分
	 * @param int   $pageBarACount 分页条a的数量，可以参考手册分页条部分
	 * @return type
	 */
	public function getPage($page, $pagesize, $url, $fields = '*', Array $where = null, Array $orderBy = array(), $pageBarOrder = array(1, 2, 3, 4, 5, 6), $pageBarACount = 10) {
		$data = array();

		if (is_array($where)) {
			$this->getDb()->where($where);
		}
		$total = $this->getDb()->select('count(*) as total')
			->from($this->getTable())
			->execute()
			->value('total');
		//这里必须重新附加条件，上面的count会重置条件
		if (is_array($where)) {
			$this->getDb()->where($where);
		}
		foreach ($orderBy as $k => $v) {
			$this->getDb()->orderBy($k, $v);
		}
		$data['items'] = $this->getDb()
				->select($fields)
				->limit(($page - 1) * $pagesize, $pagesize)
				->from($this->getTable())->execute()->rows();
		$data['page'] = Sr::page($total, $page, $pagesize, $url, $pageBarOrder, $pageBarACount);
		return $data;
	}

	/**
	 * SQL搜索
	 * @param type $page      第几页
	 * @param type $pagesize  每页多少条
	 * @param type $url       基础url，里面的{page}会被替换为实际的页码
	 * @param type $fields    select的字段，全部用*，多个字段用逗号分隔
	 * @param type $cond      是条件字符串，SQL语句where后面的部分，不要带limit
	 * @param type $values    $cond中的问号的值数组，$cond中使用?可以防止sql注入
	 * @param array $pageBarOrder   分页条组成，可以参考手册分页条部分
	 * @param int   $pageBarACount 分页条a的数量，可以参考手册分页条部分
	 * @return type
	 */
	public function search($page, $pagesize, $url, $fields, $cond, Array $values = array(), $pageBarOrder = array(1, 2, 3, 4, 5, 6), $pageBarACount = 10) {
		$data = array();
		$table = $this->getDb()->getTablePrefix() . $this->getTable();
		$rs = $this->getDb()
			->execute('select count(*) as total from ' . $table . (strpos(trim($cond), 'order') === 0 ? ' ' : ' where ') . $cond, $values);
		//如果 $cond 包含 group by，结果条数是$rs->total()
		$total = $rs->total() > 1 ? $rs->total() : $rs->value('total');
		$data['items'] = $this->getDb()
			->execute('select ' . $fields . ' from ' . $table . (strpos(trim($cond), 'order') === 0 ? ' ' : ' where ') . $cond . ' limit ' . (($page - 1) * $pagesize) . ',' . $pagesize, $values)
			->rows();
		$data['page'] = Sr::page($total, $page, $pagesize, $url, $pageBarOrder, $pageBarACount);
		return $data;
	}

}

abstract class Soter_Business {
	
}

abstract class Soter_Bean {
	
}

abstract class Soter_Task {

	protected $debug = false, $debugError = false;

	public function __construct() {
		if (!Sr::isCli()) {
			throw new Soter_Exception_500('Task only in cli mode');
		}
		if (!function_exists('shell_exec')) {
			throw new Soter_Exception_500('Function [ shell_exec ] was disabled , run task must be enabled it .');
		}
	}

	public function _execute(Soter_CliArgs $args) {
		$this->debug = $args->get('debug');
		$this->debugError = $args->get('debug-error');
		$startTime = Sr::microtime();
		$class = get_class($this);
		if ($this->debugError) {
			$_startTime = date('Y-m-d H:i:s.') . substr($startTime . '', strlen($startTime . '') - 3);
			$error = $this->execute($args);
			if ($error) {
				$this->_log('Task [ ' . $class . ' ] execute failed , started at [ ' . $_startTime . ' ], use time ' . (Sr::microtime() - $startTime) . ' ms , exited with error : [ ' . $error . ' ]');
				$this->_log('', false);
			}
		} else {
			$this->_log('Task [ ' . $class . ' ] start');
			$this->execute($args);
			$this->_log('Task [ ' . $class . ' ] end , use time ' . (Sr::microtime() - $startTime) . ' ms');
			$this->_log('', false);
		}
	}

	public function _log($msg, $time = true) {
		if ($this->debug || $this->debugError) {
			$nowTime = '' . Sr::microtime();
			echo ($time ? date('[Y-m-d H:i:s.' . substr($nowTime, strlen($nowTime) - 3) . ']') . ' [PID:' . sprintf('%- 5d', getmypid()) . '] ' : '') . $msg . "\n";
		}
	}

	public final function pidIsExists($pid) {
		if (PATH_SEPARATOR == ':') {
			//linux
			return trim(shell_exec("ps ax | awk '{ print $1 }' | grep -e \"^{$pid}$\""), "\n") == $pid;
		} else {
			//windows
			return strpos(shell_exec('tasklist /NH /FI "PID eq ' . $pid . '"'), $pid) !== false;
		}
	}

	abstract function execute(Soter_CliArgs $args);
}

abstract class Soter_Task_Single extends Soter_Task {

	public function _execute(Soter_CliArgs $args) {
		$this->debug = $args->get('debug');
		$class = get_class($this);
		$startTime = Sr::microtime();
		$this->_log('Single Task [ ' . $class . ' ] start');
		$lockFilePath = $args->get('pid');
		if (!$lockFilePath) {
			$tempDirPath = Sr::config()->getStorageDirPath();
			$key = md5(Sr::config()->getApplicationDir() .
				Sr::config()->getClassesDirName() . '/'
				. Sr::config()->getTaskDirName() . '/'
				. str_replace('_', '/', get_class($this)) . '.php');
			$lockFilePath = Sr::realPath($tempDirPath) . '/' . $key . '.pid';
		}
		if (file_exists($lockFilePath)) {
			$pid = file_get_contents($lockFilePath);
			//lockfile进程pid存在，直接返回
			if ($this->pidIsExists($pid)) {
				$this->_log('Single Task [ ' . $class . ' ] is running with pid ' . $pid . ' , now exiting...');
				$this->_log('Single Task [ ' . $class . ' ] end , use time ' . (Sr::microtime() - $startTime) . ' ms');
				$this->_log('', false);
				return;
			}
		}
		//写入进程pid到lockfile
		if (file_put_contents($lockFilePath, getmypid()) === false) {
			throw new Soter_Exception_500('can not create file : [ ' . $lockFilePath . ' ]');
		}
		$this->_log('update pid file [ ' . $lockFilePath . ' ]');
		$this->execute($args);
		@unlink($lockFilePath);
		$this->_log('clean pid file [ ' . $lockFilePath . ' ]');
		$this->_log('Single Task [ ' . $class . ' ] end , use time ' . (Sr::microtime() - $startTime) . ' ms');
		$this->_log('', false);
	}

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
		return $this->errorMessage ? $this->errorMessage : $this->getMessage();
	}

	public function getErrorCode() {
		return $this->errorCode ? $this->errorCode : $this->getCode();
	}

	public function getEnvironment() {
		$array=array(Sr::ENV_PRODUCTION=>'Sr::ENV_PRODUCTION',Sr::ENV_TESTING=>'Sr::ENV_TESTING',Sr::ENV_DEVELOPMENT=>'Sr::ENV_DEVELOPMENT');
		return $array[Sr::config()->getEnvironment()];
	}

	public function getErrorFile($safePath = FALSE) {
		$file = $this->errorFile ? $this->errorFile : $this->getFile();
		return $safePath ? Sr::safePath($file) : $file;
	}

	public function getErrorLine() {
		return $this->errorLine ? $this->errorLine : ( $this->errorFile ? $this->errorLine : $this->getLine());
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
		$trace = array_reverse($this->trace);
		$str = $isCli ? "[ Debug Backtrace ]\n" : '<div style="padding:10px;">[ Debug Backtrace ]<br/>';
		if (empty($trace)) {
			return '';
		}
		$i = 1;
		foreach ($trace as $e) {
			$file = Sr::safePath(Sr::arrayGet($e, 'file'));
			$line = Sr::arrayGet($e, 'line');
			$func = (!empty($e['class']) ? "{$e['class']}{$e['type']}{$e['function']}()" : "{$e['function']}()");
			$str.="&rarr; " . ($i++) . ".{$func} " . ($line ? "[ line:{$line} {$file} ]" : '') . ($isCli ? "\n" : '<br/>');
		}
		$str.=$isCli ? "\n" : '</div>';
		return $str;
	}

	public function renderCli() {
		return "$this->exceptionName [ " . $this->getErrorType() . " ]\n"
			. "Environment: " . $this->getEnvironment()."\n"
			. "Line: " . $this->getErrorLine() . ". " . $this->getErrorFile() . "\n"
			. "Message: " . $this->getErrorMessage() . "\n"
			. "Time: " . date('Y/m/d H:i:s T') . "\n";
	}

	public function renderHtml() {
		return '<body style="padding:0;margin:0;background:black;color:whitesmoke;">'
			. '<div style="padding:10px;background:red;font-size:18px;">' . $this->exceptionName . ' [ ' . $this->getErrorType() . ' ] </div>'
			. '<div style="padding:10px;background:black;font-size:14px;color:yellow;line-height:1.5em;">'
			. '<font color="whitesmoke">Environment: </font>' . $this->getEnvironment().'<br/>'
			. '<font color="whitesmoke">Line: </font>' . $this->getErrorLine() . ' [ ' . $this->getErrorFile(TRUE) . ' ]<br/>'
			. '<font color="whitesmoke">Message: </font>' . htmlspecialchars($this->getErrorMessage()) . '</br>'
			. '<font color="whitesmoke">Time: </font>' . date('Y/m/d H:i:s T') . '</div>'
			. '</body>';
	}

	public function renderJson() {
		$render = soter::getConfig()->getExceptionJsonRender();
		if (is_callable($render)) {
			return $render($this);
		}
		return '';
	}

	public function setHttpHeader() {
		if (!Sr::isCli()) {
			header($this->httpStatusLine);
		}
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
