<?php

require_once 'pluginfortest.php';
require_once('simpletest/autorun.php');
require_once('simpletest/browser.php');

/**
 * Soter测试案例
 */
class Test_model extends UnitTestCase {

	/**
	 * 测试hmvc模式下异常json输出
	 */
	public function testHmvcExceptionJson() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exception.html'));
		$obj = json_decode($browser->getContent());
		$this->assertTrue(!empty($obj->errorTime));
		$this->assertTrue(!empty($obj->errorFile));
		$this->assertTrue(!empty($obj->errorLine));
		$this->assertTrue(!empty($obj->errorCode));
		$this->assertTrue(!empty($obj->errorMessage));
		$flag = 'Call to undefined function none()';
		$this->assertTrue($obj->errorMessage === $flag);
	}

	/**
	 * 测试hmvc模式下异常html输出
	 */
	public function testHmvcExceptionHtml() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exceptionHtml.html'));
		$flag = '<body style="padding:0;margin:0;background:black;color:whitesmoke;">';
		$this->assertTrue(strpos($browser->getContent(), $flag) === 0);
		$flag2 = 'Call to undefined function none()';
		$this->assertTrue(strpos($browser->getContent(), $flag2) !== FALSE);
	}

	/**
	 * 测试hmvc模式下异常cli输出
	 */
	public function testHmvcExceptionCli() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exceptionCli.html'));
		$flag1 = 'Soter_Exception_500 [ ERROR ]';
		$this->assertTrue(strpos($browser->getContent(), $flag1) === 0);
		$flag2 = 'Call to undefined function none()';
		$this->assertTrue(strpos($browser->getContent(), $flag2) !== FALSE);
	}

	/**
	 * 测试hmvc模式下用户自定义异常处理显示页面
	 */
	public function testHmvcExceptionHandle() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exceptionHandle.html'));
		$flag1 = 'from hmvcException_Handle';
		$this->assertTrue($browser->getContent() === $flag1);
	}

	/**
	 * 测试hmvc模式下自定义异常LoggerWriter
	 */
	public function testHmvcExceptionLoggerWriter() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exceptionLoggerWriter.html'));
		$flag1 = 'my hmvc writer called';
		$this->assertTrue($browser->getContent() === $flag1);
	}

	/**
	 * 测试异常json输出
	 */
	public function testExceptionJson() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/exception.do'));
		$obj = json_decode($browser->getContent());
		$this->assertTrue(!empty($obj->errorTime));
		$this->assertTrue(!empty($obj->errorFile));
		$this->assertTrue(!empty($obj->errorLine));
		$this->assertTrue(!empty($obj->errorCode));
		$this->assertTrue(!empty($obj->errorMessage));
		$flag = 'Call to undefined function none()';
		$this->assertTrue($obj->errorMessage === $flag);
	}

	/**
	 * 测试异常html输出
	 */
	public function testExceptionHtml() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/exceptionHtml.do'));
		$flag = '<body style="padding:0;margin:0;background:black;color:whitesmoke;">';
		$this->assertTrue(strpos($browser->getContent(), $flag) === 0);
		$flag2 = 'Call to undefined function none()';
		$this->assertTrue(strpos($browser->getContent(), $flag2) !== FALSE);
	}

	/**
	 * 测试异常cli输出
	 */
	public function testExceptionCli() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/exceptionCli.do'));
		$flag1 = 'Soter_Exception_500 [ ERROR ]';
		$this->assertTrue(strpos($browser->getContent(), $flag1) === 0);
		$flag2 = 'Call to undefined function none()';
		$this->assertTrue(strpos($browser->getContent(), $flag2) !== FALSE);
	}

	/**
	 * 测试用户自定义异常处理显示页面
	 */
	public function testExceptionHandle() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/exceptionHandle.do'));
		$flag1 = 'from Exception_Handle';
		$this->assertTrue($browser->getContent() === $flag1);
	}

	/**
	 * 测试自定义异常LoggerWriter
	 */
	public function testExceptionLoggerWriter() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/exceptionLoggerWriter.do'));
		$flag1 = 'my writer called';
		$this->assertTrue($browser->getContent() === $flag1);
	}

}
