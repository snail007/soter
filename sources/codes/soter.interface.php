<?php

interface Soter_Logger_Writer {

	public function write(Soter_Exception $exception);
}

interface Soter_Request {

	public function getPathInfo();

	public function getQueryString();
}

interface Soter_Uri_Rewriter {

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

	public function set($key, $value, $cacheTime=0);

	public function get($key);

	public function delete($key);

	public function clean();
}
