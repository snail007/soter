<?php

class Controller_Welcome extends Soter_Controller {

	public function do_index() {
		 
	}

	/**
	 * 测试异常托管,json输出
	 */
	public function do_exception() {
		Sr::config()->setExceptionHandle(new Exception_HandleTest());
		none();
	}

	/**
	 * 测试异常托管,html输出
	 */
	public function do_exceptionHtml() {
		Sr::config()->setExceptionHandle(new Exception_HandleTestHtml());
		none();
	}

	/**
	 * 测试异常托管,cli输出
	 */
	public function do_exceptionCli() {
		Sr::config()->setExceptionHandle(new Exception_HandleTestCli());
		none();
	}
	/**
	 * 测试异常托管
	 */
	public function do_exceptionHandle() {
		Sr::config()->setExceptionHandle(new Exception_Handle());
		none();
	}
	/**
	 * 测试异常LoggerWriter
	 */
	public function do_exceptionLoggerWriter() {
		Sr::config()->setShowError(false)->addLoggerWriter(new Logger_MyWriter());
		none();
	}

}
