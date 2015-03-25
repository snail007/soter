<?php

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
