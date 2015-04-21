<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');
require_once('simpletest/browser.php');

//require_once('simpletest/browser.php');
/**
 * Soter测试案例
 * Test by snail（672308444@163.com）
 */
class TestUrl extends UnitTestCase {

	public function testUrl0() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/url.do'));
		$this->assertEqual($browser->getContent(), '/soter/tests/indexfortest.php/Welcome/index.do');
	}

	public function testUrlArgs() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/urlArgs.do'));
		$this->assertEqual($browser->getContent(), '/soter/tests/indexfortest.php/Welcome/index.do?a=v&d=d');
	}

	public function testRewriteUrl0() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/urlRewrite.do'));
		$this->assertEqual($browser->getContent(), '/soter/tests/Welcome/index.do');
	}

	public function testRewriteUrlArgs() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/urlRewriteArgs.do'));
		$this->assertEqual($browser->getContent(), '/soter/tests/Welcome/index.do?a=v&d=d');
	}

	public function testUrlPath() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/urlPath.do'));
		$this->assertEqual($browser->getContent(), '/soter/tests/public/');
	}

	public function testUrlPathRes() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/urlPathRes.do'));
		$this->assertEqual($browser->getContent(), '/soter/tests/public/css/style.css');
	}

	public function testHmvcUrl0() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/url.html'));
		$this->assertEqual($browser->getContent(), '/soter/tests/indexfortest.php/Welcome/index.do');
	}

	public function testHmvcUrlArgs() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/urlArgs.html'));
		$this->assertEqual($browser->getContent(), '/soter/tests/indexfortest.php/Welcome/index.do?a=v&d=d');
	}

	public function testHmvcRewriteUrl0() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/urlRewrite.html'));
		$this->assertEqual($browser->getContent(), '/soter/tests/Welcome/index.do');
	}

	public function testHmvcRewriteUrlArgs() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/urlRewriteArgs.html'));
		$this->assertEqual($browser->getContent(), '/soter/tests/Welcome/index.do?a=v&d=d');
	}

	public function testHmvcUrlPath() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/urlPath.html'));
		$this->assertEqual($browser->getContent(), '/soter/tests/public/');
	}

	public function testHmvcUrlPathRes() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/urlPathRes.html'));
		$this->assertEqual($browser->getContent(), '/soter/tests/public/css/style.css');
	}

}
