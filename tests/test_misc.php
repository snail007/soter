<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');
require_once('simpletest/browser.php');

class TestMisc extends UnitTestCase {

	public function TestShowError() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/showErrorTest.do'));
		$this->assertTrue(strlen($browser->getContent()) == 0);
	}

	public function TestLibrary() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/library.do'));
		$this->assertEqual($browser->getContent(),'1');
	}
	public function TestBusiness() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/business.do'));
		$this->assertEqual($browser->getContent(),'1');
	}
	public function TestDao() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/dao.do'));
		$this->assertEqual($browser->getContent(),'1');
	}
	public function TestModel() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/model.do'));
		$this->assertEqual($browser->getContent(),'1');
	}
	public function TestFunctions() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/functions.do'));
		$this->assertEqual($browser->getContent(),'myFunction');
	}
	public function TestFunctionsAuto() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/functionsAuto.do'));
		$this->assertEqual($browser->getContent(),'myFunctionAuto');
	}

}
