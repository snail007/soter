<?php

require_once 'pluginfortest.php';
require_once('simpletest/autorun.php');
require_once('simpletest/browser.php');

/**
 * Soter测试案例
 */
class Test_model extends UnitTestCase {

	/**
	 * 测试hmvc模式下notice异常
	 */
	public function testHmvcExceptionNotice() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exceptionNotice.html'));
		$obj = json_decode($browser->getContent());
		$this->assertTrue($obj->message === 'Undefined variable: none');
		$this->assertTrue($obj->type === 'NOTICE');
	}

	/**
	 * 测试hmvc模式下throw exception异常
	 */
	public function testHmvcExceptionException() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exceptionException.html'));
		$obj = json_decode($browser->getContent());
		$this->assertTrue($obj->message === 'throw test');
		$this->assertTrue($obj->type === 'Exception');
	}

	/**
	 * 测试hmvc模式下异常json输出
	 */
	public function testHmvcExceptionJson() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exception.html'));
		$obj = json_decode($browser->getContent());
		$this->assertTrue(!empty($obj->time));
		$this->assertTrue(!empty($obj->file));
		$this->assertTrue(!empty($obj->line));
		$this->assertTrue(isset($obj->code));
		$this->assertTrue(!empty($obj->message));
		$flag = 'Call to undefined function none()';
		$this->assertTrue(fixString($obj->message) === fixString($flag));
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
		$this->assertTrue(strpos(fixString($browser->getContent()), fixString($flag2)) !== FALSE);
	}

	/**
	 * 测试hmvc模式下异常cli输出
	 */
	public function testHmvcExceptionCli() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/exceptionCli.html'));
		$flag1 = 'Soter_Exception_500 [ ERROR ]';
		$this->assertTrue(strpos(strtoupper($browser->getContent()), strtoupper($flag1)) === 0);
		$flag2 = 'Call to undefined function none()';
		$this->assertTrue(strpos(fixString($browser->getContent()), fixString($flag2)) !== FALSE);
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
		$this->assertTrue(!empty($obj->time));
		$this->assertTrue(!empty($obj->file));
		$this->assertTrue(!empty($obj->line));
		$this->assertTrue(isset($obj->code));
		$this->assertTrue(!empty($obj->message));
		$flag = 'Call to undefined function none()';
		$this->assertTrue(strpos(fixString($obj->message), fixString($flag)) !== FALSE);
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
		$this->assertTrue(strpos(fixString($browser->getContent()), fixString($flag2)) !== FALSE);
	}

	/**
	 * 测试异常cli输出
	 */
	public function testExceptionCli() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/exceptionCli.do'));
		$flag1 = 'Soter_Exception_500 [ ERROR ]';
		$this->assertTrue(strpos(strtoupper($browser->getContent()), strtoupper($flag1)) === 0);
		$flag2 = 'Call to undefined function none()';
		$this->assertTrue(strpos(fixString($browser->getContent()), fixString($flag2)) !== FALSE);
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

	/**
	 * 测试notice异常
	 */
	public function testExceptionNotice() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/exceptionNotice.do'));
		$obj = json_decode($browser->getContent());
		$this->assertTrue($obj->message === 'Undefined variable: none');
		$this->assertTrue($obj->type === 'NOTICE');
	}

	/**
	 * 测试throw exception异常
	 */
	public function testExceptionException() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/exceptionException.do'));
		$obj = json_decode($browser->getContent());
		$this->assertTrue($obj->message === 'throw test');
		$this->assertTrue($obj->type === 'Exception');
	}

}
