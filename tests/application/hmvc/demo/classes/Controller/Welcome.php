<?php

class Controller_Welcome extends Soter_Controller {

	public function before($method, $args) {
		if ($method == 'testBefore') {
			echo 'hmvc-before' . $args[0];
		}
	}

	public function after($method, $args, $contents) {
		if ($method == 'testAfter') {
			echo 'hmvc-after' . $args[0] . $contents;
		} else {
			echo $contents;
		}
	}

	public function do_testBefore($a) {
		echo 'x' . $a;
	}

	public function do_testAfter() {
		echo 'test';
	}

	public function do_url1() {
		echo Sr::url('/Demo/Welcome/Index.do');
	}

	public function do_index() {
		echo 'hmvc';
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

		Sr::config()->setShowError(true)->setExceptionHandle(new Exception_Handle());
		none();
	}

	/**
	 * 测试异常LoggerWriter
	 */
	public function do_exceptionLoggerWriter() {
		Sr::config()->setShowError(false)->addLoggerWriter(new Logger_MyWriter());
		none();
	}

	/**
	 * 传递参数给方法
	 */
	public function do_args($type = 'system', $id = '002') {
		echo $type . $id;
	}

	public function do_url() {
		echo Sr::url('Welcome/index.do');
	}

	public function do_urlArgs() {
		$data = array('a' => 'v', 'd' => 'd');
		echo Sr::url('Welcome/index.do', $data);
	}

	public function do_urlRewrite() {
		Sr::config()->setIsRewrite(true);
		echo Sr::url('Welcome/index.do');
	}

	public function do_urlRewriteArgs() {
		$data = array('a' => 'v', 'd' => 'd');
		Sr::config()->setIsRewrite(true);
		echo Sr::url('Welcome/index.do', $data);
	}

	public function do_urlPath() {
		echo Sr::urlPath('public');
	}

	public function do_urlPathRes() {
		echo Sr::urlPath('public/css/style.css', false);
	}

	public function do_viewLoadParent() {
		Sr::view()->loadParent('test_load');
	}

	public function do_viewLoadParentData() {
		$data['a'] = 'b';
		Sr::view()->loadParent('test_load_data', $data);
	}

	public function do_viewLoadParentDataReturn() {
		$data['a'] = 'b';
		echo Sr::view()->loadParent('test_load_data_return', $data, true);
	}

	public function do_hvmcLoadAndParentLoad() {
		Sr::view()->load('header_hmvc');
	}

	public function do_viewLoad() {
		Sr::view()->load('hmvc_test_load');
	}

	public function do_viewLoadData() {
		$data['a'] = 'bb';
		Sr::view()->load('hmvc_test_load_data', $data);
	}

	public function do_viewLoadDataReturn() {
		$data['a'] = 'bb';
		echo Sr::view()->load('hmvc_test_load_data_return', $data, true);
	}

	public function do_viewSet() {
		Sr::view()->set('a', 'bb');
		Sr::view()->load('hmvc_test_load_data');
	}

	public function do_viewAdd() {
		Sr::view()->add('a', 'bb');
		Sr::view()->load('hmvc_test_load_data');
	}

	public function do_viewAddSet() {
		Sr::view()->set('a', 'bb');
		Sr::view()->add('a', 'cc');
		Sr::view()->load('hmvc_test_load_data');
	}

	public function do_viewSetAdd() {
		Sr::view()->add('a', 'bb');
		Sr::view()->set('a', 'cc');
		Sr::view()->load('hmvc_test_load_data');
	}

	public function do_message() {
		Sr::message('', '', 0, 'message');
	}

}
