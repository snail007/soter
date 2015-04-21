<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');
require_once('simpletest/browser.php');

/**
 * Soter测试案例
 * Test by snail（672308444@163.com）
 */
class TestView extends UnitTestCase {

	public function testLoad() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/viewLoad.do'));
		$this->assertEqual($browser->getContent(), 'atest_load');
	}

	public function testLoadData() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/viewLoadData.do'));
		$this->assertEqual($browser->getContent(), 'b');
	}

	public function testLoadDataRetrun() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/viewLoadDataReturn.do'));
		$this->assertEqual($browser->getContent(), 'b');
	}

	public function testLoadParent() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewLoadParent.html'));
		$this->assertEqual($browser->getContent(), 'atest_load');
	}

	public function testLoadParentData() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewLoadParentData.html'));
		$this->assertEqual($browser->getContent(), 'b');
	}

	public function testLoadParentDataRetrun() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewLoadParentDataReturn.html'));
		$this->assertEqual($browser->getContent(), 'b');
	}

	public function testHvmcLoad() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewLoad.html'));
		$this->assertEqual($browser->getContent(), 'atest_load');
	}

	public function testHvmcLoadData() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewLoadData.html'));
		$this->assertEqual($browser->getContent(), 'bb');
	}

	public function testHvmcLoadDataRetrun() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewLoadDataReturn.html'));
		$this->assertEqual($browser->getContent(), 'bb');
	}

	public function testSet() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/viewSet.do'));
		$this->assertEqual($browser->getContent(), 'b');
	}

	public function testAdd() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/viewAdd.do'));
		$this->assertEqual($browser->getContent(), 'b');
	}

	public function testSetAdd() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/viewSetAdd.do'));
		$this->assertEqual($browser->getContent(), 'c');
	}

	public function testAddSet() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Welcome/viewAddSet.do'));
		$this->assertEqual($browser->getContent(), 'b');
	}

	public function testHmvcSet() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewSet.html'));
		$this->assertEqual($browser->getContent(), 'bb');
	}

	public function testHmvcAdd() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewAdd.html'));
		$this->assertEqual($browser->getContent(), 'bb');
	}

	public function testHmvcSetAdd() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewSetAdd.html'));
		$this->assertEqual($browser->getContent(), 'cc');
	}

	public function testHmvcAddSet() {
		$browser = new SimpleBrowser();
		$browser->get(testUrl('Demo/Welcome/viewAddSet.html'));
		$this->assertEqual($browser->getContent(), 'bb');
	}

}
