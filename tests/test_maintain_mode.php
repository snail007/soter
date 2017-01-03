<?php

require_once 'pluginfortest.php';
//require_once('simpletest/web_tester.php');
require_once('simpletest/autorun.php');

//require_once('simpletest/browser.php');
/**
 * Soter测试案例
 * Test by snail（672308444@163.com）
 */
class TestMaintainMode extends UnitTestCase {

	public function test_whitelist() {
		Sr::config()->setIsMaintainMode(true);
		Sr::config()->setMaintainIpWhitelist(array('127.0.0.1', '192.168.0.2/32'));
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		$this->assertTrue($this->canRun());
		$_SERVER['REMOTE_ADDR'] = '192.168.0.3';
		$this->assertFalse($this->canRun());
		$_SERVER['REMOTE_ADDR'] = '192.168.1.2';
		$this->assertFalse($this->canRun());
		$_SERVER['REMOTE_ADDR'] = '192.168.0.2';
		$this->assertTrue($this->canRun());
		Sr::config()->setMaintainIpWhitelist(array('192.168.0.2/24'));
		$_SERVER['REMOTE_ADDR'] = '192.168.1.2';
		$this->assertFalse($this->canRun());
		$_SERVER['REMOTE_ADDR'] = '192.168.0.5';
		$this->assertTrue($this->canRun());
	}

	public function canRun() {
		foreach (Sr::config()->getMaintainIpWhitelist() as $ip) {
			$info = explode('/', $ip);
			$netmask = empty($info[1]) ? '32' : $info[1];
			if (Sr::ipInfo(Sr::clientIp() . '/' . $netmask, 'netaddress') == Sr::ipInfo($info[0] . '/' . $netmask, 'netaddress')) {
				return true;
			}
		}
		return false;
	}

}
