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
		$this->assertEqual($browser->getContent(), '1');
	}

	public function TestBusiness() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/business.do'));
		$this->assertEqual($browser->getContent(), '1');
	}

	public function TestDao() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/dao.do'));
		$this->assertEqual($browser->getContent(), '1');
	}

	public function TestModel() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/model.do'));
		$this->assertEqual($browser->getContent(), '1');
	}

	public function TestFunctions() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/functions.do'));
		$this->assertEqual($browser->getContent(), 'myFunction');
	}

	public function TestFunctionsAuto() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/functionsAuto.do'));
		$this->assertEqual($browser->getContent(), 'myFunctionAuto');
	}

	public function TestArgs() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/args.do'));
		$this->assertEqual($browser->getContent(), 'system002');
		$browser->get(testUrl('Welcome/args-cat.do'));
		$this->assertEqual($browser->getContent(), 'cat002');
		$browser->get(testUrl('Welcome/args-cat-001.do'));
		$this->assertEqual($browser->getContent(), 'cat001');
		$browser->get(testUrl('Welcome/args--001.do'));
		$this->assertEqual($browser->getContent(), '001');
		$browser->get(testUrl('Welcome/args-cat-.do'));
		$this->assertEqual($browser->getContent(), 'cat');
		//hmvc
		$browser->get(testUrl('Demo/Welcome/args.html'));
		$this->assertEqual($browser->getContent(), 'system002');
		$browser->get(testUrl('Demo/Welcome/args-cat.html'));
		$this->assertEqual($browser->getContent(), 'cat002');
		$browser->get(testUrl('Demo/Welcome/args-cat-001.html'));
		$this->assertEqual($browser->getContent(), 'cat001');
		$browser->get(testUrl('Demo/Welcome/args--001.html'));
		$this->assertEqual($browser->getContent(), '001');
		$browser->get(testUrl('Demo/Welcome/args-cat-.html'));
		$this->assertEqual($browser->getContent(), 'cat');
	}

	function testSetSrMethods() {
		Sr::config()->setSrMethods(array(
		    'task' => 'Model',
		    'testMethod' => function($test = null) {
			    return $test;
		    }
		));
		$this->assertIsA(Sr::task('TestModel'), 'Model_TestModel');
		$this->assertEqual(Sr::testMethod('test'), 'test');
	}

	function testEncodeDecode() {
		Sr::config()->setEncryptKey('134134');
		$this->assertEqual(Sr::encrypt('123'), Sr::encrypt('123', '134134'));
		$this->assertEqual(Sr::decrypt(Sr::encrypt('123')), Sr::decrypt(Sr::encrypt('123'), '134134'));
	}

}
