<?php

class Controller_Welcome extends Soter_Controller {

	public function do_index() {
		
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

	/**
	 * 测试异常动态改变env ENV_DEVELOPMENT
	 */
	public function do_envTestDevelopment() {
		Sr::config()->setEnvironment(Sr::ENV_DEVELOPMENT);
		echo Sr::config()->getConfigCurrentDirName();
	}

	/**
	 * 测试异常动态改变env ENV_TESTING
	 */
	public function do_envTestTesting() {
		Sr::config()->setEnvironment(Sr::ENV_TESTING);
		echo Sr::config()->getConfigCurrentDirName();
	}

	/**
	 * 测试异常动态改变env ENV_PRODUCTION
	 */
	public function do_envTestProduction() {
		Sr::config()->setEnvironment(Sr::ENV_PRODUCTION);
		echo Sr::config()->getConfigCurrentDirName();
	}

	/**
	 * 测试showError设置
	 */
	public function do_showErrorTest() {
		Sr::config()->setShowError(false);
		none();
	}

	/**
	 * 包内部加载配置测试
	 */
	public function do_configPackage() {
		echo Sr::factory('Misc')->config();
	}

	/**
	 * 加载类库
	 */
	public function do_library() {
		echo (Sr::library('Misc') instanceof Misc) ? '1' : 0;
	}

	/**
	 * 加载dao
	 */
	public function do_dao() {
		echo (Sr::dao('TestDao') instanceof Dao_TestDao) ? '1' : 0;
	}

	/**
	 * 加载business
	 */
	public function do_business() {
		echo (Sr::business('TestBusiness') instanceof Business_TestBusiness) ? '1' : 0;
	}

	/**
	 * 加载model
	 */
	public function do_model() {
		echo (Sr::model('TestModel') instanceof Model_TestModel) ? '1' : 0;
	}
	/**
	 * 加载functions
	 */
	public function do_functions() {
		Sr::functions('functions');
		echo myFunction();
	}
	/**
	 * 自动加载functions
	 */
	public function do_functionsAuto() {
		echo myFunctionAuto();
	}
	

}
