<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');
require_once('simpletest/browser.php');

class TestEnv extends UnitTestCase {

	public function TestEnv1() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/envTestDevelopment.do'));
		$this->assertEqual($browser->getContent(), 'development');
	}

	public function TestEnv2() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/envTestProduction.do'));
		$this->assertEqual($browser->getContent(), 'production');
	}

	public function TestEnv3() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/envTestTesting.do'));
		$this->assertEqual($browser->getContent(), 'testing');
	}

}
