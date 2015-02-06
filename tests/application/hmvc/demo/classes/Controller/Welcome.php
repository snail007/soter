<?php

class Controller_Welcome extends Soter_Controller {

	public function do_index() {
		myFunction();
		Sr::dao('TestDao');
		Sr::business('TestBusiness');
		// xxcc();
		new Misc();
		return 'hmvc';
	}

	/**
	 * 测试notice异常
	 */
	public function do_exceptionNotice() {
		Sr::config()->setExceptionHandle(new Exception_HandleTest());
		echo $none;
	}

	/**
	 * 测试exception异常
	 */
	public function do_exceptionException() {
		Sr::config()->setExceptionHandle(new Exception_HandleTest());
		throw new Soter_Exception_500('throw test');
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
